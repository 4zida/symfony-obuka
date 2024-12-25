<?php

namespace App\Tests\Controller;

use App\Document\Place;
use App\Document\PriceStats;
use App\Tests\BaseTestController;
use Doctrine\ODM\MongoDB\MongoDBException;
use Nebkam\FluentTest\RequestBuilder;
use Symfony\Component\HttpFoundation\Request;

class PriceStatsControllerTest extends BaseTestController
{
    private static PriceStats $priceStats;
    private static Place $place;

    /**
     * @throws MongoDBException
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$place = self::createTestPlace();
        self::persistDocument(self::$place);
        self::$priceStats = self::createTestPriceStats(self::$place);
        self::persistDocument(self::$priceStats);

        self::flushDocuments();

        self::ensureKernelShutdown();
    }

    public function testIndex(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/price-stats/')
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent()[0];
        self::assertNotEmpty($content);
        self::assertEquals(500_000, $content['maxPrice']);
        self::assertEquals(50_000, $content['minPrice']);
        self::assertEquals(250_000, $content['averagePrice']);
    }

    public function testShow(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/price-stats/' . self::$place->getId() . DIRECTORY_SEPARATOR . self::$priceStats->getType())
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertEquals(500_000, $content['maxPrice']);
        self::assertEquals(50_000, $content['minPrice']);
        self::assertEquals(250_000, $content['averagePrice']);
    }

    public function testGetPriceStats(): void
    {
        RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/price-stats')
            ->getResponse();
        self::assertResponseIsSuccessful();
    }

    /**
     * @throws MongoDBException
     */
    public static function tearDownAfterClass(): void
    {
        self::removeDocumentById(PriceStats::class, self::$priceStats->getId());
        self::removeDocumentById(Place::class, self::$place->getId());

        parent::tearDownAfterClass();
    }

}
