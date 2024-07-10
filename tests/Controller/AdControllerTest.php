<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Nebkam\FluentTest\RequestBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

class AdControllerTest extends WebTestCase
{
    private static User $agent;
    private static ?int $agentId;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$agent = (new User())
            ->setEmail('agent@example.com')
            ->setRoles(['ROLE_AGENT'])
            ->setPasswordNoHash('12345678')
            ->setPassword('12345678')
            ->setRole('TestRole')
            ->setName('TestName')
            ->setSurname('TestSurname')
            ->setCompany(null)
        ;
        self::$agentId = self::$agent->getId();

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
        dump($content);
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/%d', self::$agentId)
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
                "dateTime" => "10/10/2022",
                "unixTime" => time()
            ])
            ->getResponse();
        self::assertResponseIsSuccessful();

        dump($response->getResponse()->getContent());
    }
}
