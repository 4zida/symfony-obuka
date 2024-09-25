<?php

namespace App\Tests\Controller\Ad;

use App\Document\Ad\Ad;
use App\Entity\Company;
use App\Entity\User;
use App\Tests\BaseTestController;
use App\Tests\DocumentManagerAwareTrait;
use App\Tests\EntityManagerAwareTrait;
use App\Util\ResponseMessage;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Nebkam\FluentTest\RequestBuilder;
use Symfony\Component\HttpFoundation\Request;

class AdControllerTest extends BaseTestController
{
    use DocumentManagerAwareTrait;
    use EntityManagerAwareTrait;

    private static ?Ad $ad;
    private static ?Ad $adDelete;
    private static ?User $user;
    private static ?Company $company;


    /**
     * @throws MongoDBException
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$company = self::createTestCompany();
        self::persistEntity(self::$company);

        self::$user = self::createTestUser(self::$company);
        self::persistEntity(self::$user);

        self::$ad = self::createTestAd(self::$company, self::$user);
        self::persistDocument(self::$ad);

        self::$adDelete = self::createTestAd(self::$company, self::$user);
        self::persistDocument(self::$adDelete);

        self::ensureKernelShutdown();
    }

    public function testActivate(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/activate/' . self::$ad->getId())
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getRawContent();
        self::assertEquals(ResponseMessage::AD_ACTIVATED, $content);
    }

    public function testDeactivate(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/deactivate/' . self::$ad->getId())
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getRawContent();
        self::assertEquals(ResponseMessage::AD_DEACTIVATED, $content);
    }

    public function testIndex(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/')
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertIsArray($content);
    }

    /**
     * @throws Exception
     */
    public function testShow(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/' . self::$ad->getId())
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertEquals(self::$ad->getId(), $content['id']);
    }

    public function testCreate(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_POST)
            ->setUri('/api/ad/')
            ->setJsonContent(self::$adJsonData)
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getRawContent();
        self::assertEquals(ResponseMessage::AD_CREATED, $content);
    }

    /**
     * @throws Exception
     */
    public function testUpdate(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_PATCH)
            ->setUri('/api/ad/' . self::$ad->getId())
            ->setJsonContent(self::$adJsonData)
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getRawContent();
        self::assertEquals(ResponseMessage::AD_UPDATED, $content);
    }

    public function testFindById(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/search/' . self::$ad->getId())
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertIsArray($content);
        self::assertEquals(self::$ad->getId(), $content["id"]);
    }

    public function testFindByUser(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/search/user/' . self::$user->getId())
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertIsArray($content);
        self::assertEquals(self::$user->getId(), $content[0]["userId"]);
    }

    public function testFindByCompany(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/search/company/' . self::$company->getId())
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertIsArray($content);
        self::assertEquals(self::$company->getId(), $content[0]["companyId"]);
    }

    public function testFindByAddress(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/search/address/' . self::$ad->getAddress())
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertIsArray($content);
        self::assertEquals(self::$ad->getAddress(), $content[0]["address"]);
    }

    public function testFindByFloor(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/search/floor/' . self::$ad->getFloor())
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertIsArray($content);
        self::assertEquals(self::$ad->getFloor(), $content[0]["floor"]);
    }

    public function testDelete(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_DELETE)
            ->setUri('/api/ad/' . self::$adDelete->getId())
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getRawContent();
        self::assertEquals(ResponseMessage::AD_DELETED, $content);
    }

    public function testAdminIndex(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/admin/ad')
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertIsArray($content);
    }

    public function testAdminShow(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/admin/ad/' . self::$ad->getId())
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertEquals(self::$ad->getId(), $content['id']);
    }

    public function testCountAds(): void
    {
        RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/count')
            ->getResponse();
        self::assertResponseIsSuccessful();
    }

    public function testGetDetails(): void
    {
        RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/details/' . self::$ad->getId())
            ->getResponse();
        self::assertResponseIsSuccessful();
    }

    public function testGetAdUserInfo(): void
    {
        RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/details/' . self::$ad->getId() . '/user-info')
            ->getResponse();
        self::assertResponseIsSuccessful();
    }

    public function testAggregationTest(): void
    {
        RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/aggregate')
            ->getResponse();
        self::assertResponseIsSuccessful();
    }

    /**
     * @throws MongoDBException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public static function tearDownAfterClass(): void
    {
        self::removeDocumentById(Ad::class, self::$ad->getId());
        self::removeDocumentById(Ad::class, self::$adDelete->getId());
        self::removeEntityById(User::class, self::$user->getId());
        self::removeEntityById(Company::class, self::$company->getId());

        parent::tearDownAfterClass();
    }
}
