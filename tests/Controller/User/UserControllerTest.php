<?php

namespace App\Tests\Controller\User;

use App\Entity\Company;
use App\Entity\User;
use App\Tests\BaseTestController;
use App\Tests\EntityManagerAwareTrait;
use App\Util\ResponseMessage;
use Doctrine\ORM\Exception\ORMException;
use Nebkam\FluentTest\RequestBuilder;
use Symfony\Component\HttpFoundation\Request;

class UserControllerTest extends BaseTestController
{
    use EntityManagerAwareTrait;

    public static ?User $user;
    public static ?Company $company;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$company = self::createTestCompany();
        self::persistEntity(self::$company);

        self::$user = self::createTestUser(self::$company);
        self::persistEntity(self::$user);

        self::ensureKernelShutdown();
    }

    public function testIndex(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/user/')
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertIsArray($content);
    }

    public function testCreate(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_POST)
            ->setUri('/api/user/')
            ->setJsonContent(self::$userJsonData)
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getRawContent();
        self::assertNotEmpty($content);
        self::assertEquals(ResponseMessage::USER_CREATED, $content);
    }

    public function testShow(): void
    {
        // id
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/user/' . self::$user->getId())
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertIsArray($content);
        self::assertEquals(self::$user->getId(), $content['id']);
    }

    public function testUpdate(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_PATCH)
            ->setUri('/api/user/' . self::$user->getId())
            ->setJsonContent([
                "name" => "Test User UPDATED"
            ])
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getRawContent();
        self::assertNotEmpty($content);
        self::assertEquals(ResponseMessage::USER_UPDATED, $content);
    }

    public function testDelete(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_DELETE)
            ->setUri('/api/user/' . self::$user->getId())
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getRawContent();
        self::assertEquals(ResponseMessage::USER_DELETED, $content);
    }

    public function testFindById(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/user/search/' . self::$user->getId())
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertIsArray($content);
        self::assertEquals(self::$user->getId(), $content["id"]);
    }

    public function testFindByRole(): void
    {
        $role = self::$user->getRole()->value;
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/user/search/role/' . $role)
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertIsArray($content);
        self::assertEquals($role, $content[0]["role"]);
    }

    public function testFindByCompany(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/user/search/company/' . self::$company->getId())
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertIsArray($content);
        self::assertEquals(self::$company->getId(), $content[0]["company"]["id"]);
    }

    public function testAdminIndex(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/admin/user')
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
            ->setUri('/api/admin/user/' . self::$user->getId())
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertEquals(self::$user->getId(), $content['id']);
    }

    public function testShowWithPhone(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/user/phone/' . self::$user->getId())
            ->getResponse();
        self::assertResponseIsSuccessful();
    }

    /**
     * @throws ORMException
     */
    public static function tearDownAfterClass(): void
    {
        self::removeEntityById(User::class, self::$user->getId());
        self::removeEntityById(Company::class, self::$company->getId());

        parent::tearDownAfterClass();
    }
}
