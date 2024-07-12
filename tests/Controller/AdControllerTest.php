<?php

namespace App\Tests\Controller;

use App\Document\Ad;
use App\Entity\Company;
use App\Entity\User;
use App\Tests\DocumentManagerAwareTrait;
use App\Tests\EntityManagerAwareTrait;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Nebkam\FluentTest\RequestBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class AdControllerTest extends WebTestCase
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
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$company = (new Company())
            ->setName("Test Company")
            ->setAddress("Test Address");
        self::$companyId = self::persistEntity(self::$company);

        self::$user = (new User())
            ->setName("Test Agent")
            ->setRole("Test Role")
            ->setPassword("testPassword")
            ->setRoles([])
            ->setSurname("Test Surname")
            ->setEmail("test@email.com")
            ->setCompany(self::$company)
            ->setPasswordNoHash("testPassword");
        self::$userId = self::persistEntity(self::$user);

        self::$agent = (new Ad())
            ->setName("AdTest")
            ->setUrl("test.url")
            ->setDescription("Description test")
            ->setDateTime(date("Y-m-d H:i:s"))
            ->setUnixTime(time())
            ->setUserId(self::$userId)
            ->setCompanyId(self::$companyId)
        ;
        self::$agentId = self::persistDocument(self::$agent);

        self::flushDocuments();

        self::ensureKernelShutdown();
    }

    public function testIndex() : void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/')
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getResponse()->getContent();
        self::assertJson($content);
    }

    /**
     * @throws \Exception
     */
    public function testShow(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/'.self::$agentId)
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getResponse()->getContent();
        $ad = self::findDocumentById(Ad::class, self::$agentId);
        self::assertNotEmpty($content);
        self::assertEquals(self::$agentId, $ad->getId());
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
                "dateTime" => "10/10/2022",
                "unixTime" => time()
            ])
            ->getResponse();
        self::assertResponseIsSuccessful();
    }

    /**
     * @throws \Exception
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

        $content = $response->getResponse()->getContent();
        $ad = self::findDocumentById(Ad::class, self::$agentId);
        self::assertNotEmpty($content);
        self::assertEquals(self::$agentId, $ad->getId());
        self::assertEquals("Ad updated.", $content);
        self::assertEquals("test Ad UPDATED", $ad->getName());
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
