<?php

namespace App\Tests\Controller;

use App\Document\Ad;
use App\Tests\DocumentManagerAwareTrait;
use Doctrine\ODM\MongoDB\MongoDBException;
use Nebkam\FluentTest\RequestBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class AdControllerTest extends WebTestCase
{
    use DocumentManagerAwareTrait;
    private static ?Ad $agent;
    private static ?string $agentId;

    /**
     * @throws MongoDBException
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$agent = (new Ad())
            ->setName("AdTest")
            ->setUrl("test.url")
            ->setDescription("Description test")
            ->setDateTime(date("Y-m-d H:i:s"))
            ->setUnixTime(time())
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
        dump($content);
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

        dump($response->getResponse()->getContent());
    }

    /**
     * @throws MongoDBException
     */
    public static function tearDownAfterClass(): void
    {
        self::removeDocumentById(Ad::class, self::$agentId);

        parent::tearDownAfterClass();
    }
}
