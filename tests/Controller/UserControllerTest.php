<?php

namespace App\Tests\Controller;

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

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$agent = (new User())
            ->setName("Test Agent")
            ->setRole("Test Role")
            ->setPassword("testPassword")
            ->setRoles([])
            ->setSurname("Test Surname")
            ->setEmail("test@ts34.com")
            ->setCompany(null)
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
        dump($content);
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

        dump($response->getResponse()->getContent());
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
        $this->assertResponseIsSuccessful();

        $content = $response->getResponse()->getContent();
        $user = self::findEntity(User::class, self::$agentId);
        self::assertNotEmpty($content);
        self::assertJson($content);
        self::assertEquals(self::$agentId, $user->getId());
        dump($content, $user);
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
