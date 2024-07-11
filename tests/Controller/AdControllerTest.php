<?php

namespace App\Tests\Controller;

use App\Document\Ad;
use Nebkam\FluentTest\RequestBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class AdControllerTest extends WebTestCase
{
    private static ?Ad $agent;
    private static ?string $agentId;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$agent = (new Ad())
            ->setId("test123test123")
            ->setName("AdTest")
            ->setUrl("test.url")
            ->setDescription("Description test")
            ->setDateTime(date("Y-m-d H:i:s"))
            ->setUnixTime(time())
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
