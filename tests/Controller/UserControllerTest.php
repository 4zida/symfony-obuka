<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Nebkam\FluentTest\RequestBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class UserControllerTest extends WebTestCase
{
    public static ?User $agent;
    public static ?int $agentId;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$agent = (new User())
            ->setId(1)
            ->setName("Test Agent")
            ->setRole("Test Role")
            ->setPassword("testPassword")
            ->setRoles([])
            ->setSurname("Test Surname")
            ->setEmail("test@test.com")
            ->setCompany(null);
        self::$agentId = self::$agent->getId();

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

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/user/%d', self::$agentId)
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertEquals(self::$agentId, $content['id']);
    }
}
