<?php

namespace App\Tests\Controller;

use App\Document\Ad;
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
use Random\RandomException;
use Symfony\Component\HttpFoundation\Request;

class AdControllerTest extends BaseTestController
{
    use DocumentManagerAwareTrait;
    use EntityManagerAwareTrait;
    private static ?Ad $agent;
    private static ?string $agentId;
    private static ?Ad $agentDelete;
    private static ?string $agentDeleteId;
    private static ?User $user;
    private static ?int $userId;
    private static ?Company $company;
    private static ?int $companyId;
    private ?array $agentJsonData = [
        "name" => "test",
        "description" => "test description",
        "url" => "https://symfony.com/doc/current/testing/database.html",
        "address" => "test address",
        "floor" => -1,
        "m2" => 50
    ];

    /**
     * @throws MongoDBException
     * @throws RandomException
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$company = self::createTestCompany();
        self::$companyId = self::persistEntity(self::$company);

        self::$user = self::createTestUser(self::$company);
        self::$userId = self::persistEntity(self::$user);

        self::$agent = self::createTestAd(self::$company, self::$user);
        self::$agentId = self::persistDocument(self::$agent);

        self::$agentDelete = self::createTestAd(self::$company, self::$user);
        self::$agentDeleteId = self::persistDocument(self::$agentDelete);

        self::ensureKernelShutdown();
    }

    public function testActivate(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/activate/'.self::$agentId)
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getRawContent();
        self::assertEquals(ResponseMessage::AD_ACTIVATED, $content);
    }

    public function testDeactivate(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/deactivate/'.self::$agentId)
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getRawContent();
        self::assertEquals(ResponseMessage::AD_DEACTIVATED, $content);
    }

    public function testIndex() : void
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
            ->setUri('/api/ad/'.self::$agentId)
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertEquals(self::$agentId, $content['id']);
    }

    public function testCreate() : void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_POST)
            ->setUri('/api/ad/')
            ->setJsonContent($this->agentJsonData)
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
            ->setUri('/api/ad/'.self::$agentId)
            ->setJsonContent($this->agentJsonData)
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getRawContent();
        self::assertEquals(ResponseMessage::AD_UPDATED, $content);
    }

    public function testFindById(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/search/'.self::$agentId)
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertIsArray($content);
        self::assertEquals(self::$agentId, $content["id"]);
    }

    public function testFindByUser(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/search/user/'.self::$userId)
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertIsArray($content);
        self::assertEquals(self::$userId, $content[0]["userId"]);
    }

    public function testFindByCompany(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/search/company/'.self::$companyId)
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertIsArray($content);
        self::assertEquals(self::$companyId, $content[0]["companyId"]);
    }

//    public function testFindByAddress(): void
//    {
//        $response = RequestBuilder::create(self::createClient())
//            ->setMethod(Request::METHOD_GET)
//            ->setUri('/api/ad/search/address/'.self::$agent->getAddress())
//            ->getResponse();
//        self::assertResponseIsSuccessful();
//
//        $content = $response->getJsonContent();
//        self::assertNotEmpty($content);
//        self::assertIsArray($content);
//        self::assertEquals(self::$agent->getAddress(), $content[0]["address"]);
//    }
//
//    public function testFindByFloor(): void
//    {
//        $response = RequestBuilder::create(self::createClient())
//            ->setMethod(Request::METHOD_GET)
//            ->setUri('/api/ad/search/floor/'.self::$agent->getFloor())
//            ->getResponse();
//        self::assertResponseIsSuccessful();
//
//        $content = $response->getJsonContent();
//        self::assertNotEmpty($content);
//        self::assertIsArray($content);
//        self::assertEquals(self::$agent->getFloor(), $content[0]["floor"]);
//    }

    public function testDelete(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_DELETE)
            ->setUri('/api/ad/'.self::$agentDeleteId)
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
            ->setUri('/api/admin/ad/'.self::$agentId)
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertEquals(self::$agentId, $content['id']);
    }

    /**
     * @throws MongoDBException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public static function tearDownAfterClass(): void
    {
        self::removeDocumentById(Ad::class, self::$agentId);
        self::removeDocumentById(Ad::class, self::$agentDeleteId);
        self::removeEntityById(User::class, self::$userId);
        self::removeEntityById(Company::class, self::$companyId);

        parent::tearDownAfterClass();
    }
}
