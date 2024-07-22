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
    private static ?User $user;
    private static ?int $userId;
    private static ?Company $company;
    private static ?int $companyId;

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

        self::ensureKernelShutdown();
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
            ->setJsonContent([
                "name" => "test",
                "description" => "test description",
                "url" => "https://symfony.com/doc/current/testing/database.html",
                "address" => "test address",
                "floor" => 3
            ])
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
            ->setJsonContent([
                "name" => "test Ad UPDATED",
            ])
            ->getResponse();
        self::assertResponseIsSuccessful();

        self::assertEquals(ResponseMessage::AD_UPDATED, $response->getResponse()->getContent());
    }

    public function testFindById(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/search/'.self::$agentId)
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
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
        self::assertEquals(self::$companyId, $content[0]["companyId"]);
    }

    /**
     * @throws MongoDBException
     * @throws RandomException
     */
    public function testDelete(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_DELETE)
            ->setUri('/api/ad/'.self::persistDocument(self::createTestAd(self::$company, self::$user)))
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getRawContent();
        self::assertEquals(ResponseMessage::AD_DELETED, $content);
    }

    /**
     * @throws MongoDBException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public static function tearDownAfterClass(): void
    {
        self::removeDocumentById(Ad::class, self::$agentId);
        self::removeEntityById(User::class, self::$userId);
        self::removeEntityById(Company::class, self::$companyId);

        parent::tearDownAfterClass();
    }
}
