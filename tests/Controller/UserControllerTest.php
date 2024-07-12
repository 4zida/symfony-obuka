<?php

namespace App\Tests\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Tests\EntityManagerAwareTrait;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Nebkam\FluentTest\RequestBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class UserControllerTest extends WebTestCase
{
    use EntityManagerAwareTrait;
    public static ?User $agent;
    public static ?int $agentId;
    public static ?Company $company;
    public static ?int $companyId;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$company = (new Company())
            ->setName("Test Company")
            ->setAddress("Test Address");
        self::$companyId = self::persistEntity(self::$company);

        self::$agent = (new User())
            ->setName("Test Agent")
            ->setRole("Test Role")
            ->setPassword("testPassword")
            ->setRoles([])
            ->setSurname("Test Surname")
            ->setEmail("test@email.com")
            ->setCompany(self::$company)
            ->setPasswordNoHash("testPassword");
        self::$agentId = self::persistEntity(self::$agent);

        self::flushEntities();

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
    }

    public function testCreate(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_POST)
            ->setUri('/api/user/')
            ->setJsonContent([
                "name" => "Test User",
                "role" => "Test Role",
                "surname" => "Test Surname",
                "password" => "Test Password",
                "email" => "test@gmail.com",
                "roles" => [],
                "company" => null
            ])
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getResponse()->getContent();
        self::assertNotEmpty($content);
        self::assertEquals('User created.', $content);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function testShow(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/user/'.self::$agentId)
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getResponse()->getContent();
        $user = self::findEntity(User::class, self::$agentId);
        self::assertNotEmpty($content);
        self::assertJson($content);
        self::assertEquals(self::$agentId, $user->getId());
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function testUpdate(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_PATCH)
            ->setUri('/api/user/'.self::$agentId)
            ->setJsonContent([
                "name" => "Test User UPDATED",
                "role" => "Test Role UPDATED"
            ])
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getResponse()->getContent();
        $user = self::findEntity(User::class, self::$agentId);
        self::assertNotEmpty($content);
        self::assertEquals("User updated.", $content);
        self::assertEquals(self::$agentId, $user->getId());
    }

    public function testFindById(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/user/search/'.self::$agentId)
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertEquals(self::$agentId, $content["id"]);
    }

    public function testFindByRole(): void
    {
        $role = self::$agent->getRole();
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/user/search/role/'.$role)
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertEquals($role, $content[0]["role"]);
    }

    public function testFindByCompany(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/user/search/company/'.self::$companyId)
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertEquals(self::$companyId, $content[0]["company"]["id"]);
    }

    /**
     * @throws ORMException
     */
    public static function tearDownAfterClass(): void
    {
        self::removeEntityById(User::class, self::$agent->getId());

        parent::tearDownAfterClass();
    }
}
