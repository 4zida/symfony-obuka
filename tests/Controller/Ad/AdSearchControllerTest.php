<?php

namespace App\Tests\Controller\Ad;

use App\Document\Ad\Ad;
use App\Document\AdFor;
use App\Entity\Company;
use App\Entity\User;
use App\Tests\BaseTestController;
use App\Tests\DocumentManagerAwareTrait;
use App\Tests\EntityManagerAwareTrait;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Nebkam\FluentTest\RequestBuilder;
use Random\RandomException;
use Symfony\Component\HttpFoundation\Request;

class AdSearchControllerTest extends BaseTestController
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

    public function testSearch(): void
    {
        // floor search testing
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/search')
            ->setJsonContent([
                'floorFrom' => 5,
                'floorTo' => 10,
            ])
            ->getResponse();
        self::assertResponseIsSuccessful();
        $content = $response->getJsonContent();
        self::assertNotNull($content);
        foreach ($content as $ad) {
            self::assertGreaterThanOrEqual(5, $ad['floor']);
            self::assertLessThanOrEqual(10, $ad['floor']);
        }

        // m2 search testing
        $response = RequestBuilder::create(self::getClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/search')
            ->setJsonContent([
                'm2From' => 20,
                'm2To' => 30,
            ])
            ->getResponse();
        self::assertResponseIsSuccessful();
        $content = $response->getJsonContent();
        self::assertNotNull($content);
        foreach ($content as $ad) {
            self::assertGreaterThanOrEqual(20, $ad['m2']);
            self::assertLessThanOrEqual(30, $ad['m2']);
        }

        // address search testing
        $response = RequestBuilder::create(self::getClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/search')
            ->setJsonContent([
                'address' => self::$agent->getAddress()
            ])
            ->getResponse();
        self::assertResponseIsSuccessful();
        $content = $response->getJsonContent();
        self::assertNotNull($content);
        foreach ($content as $ad) {
            self::assertEquals(self::$agent->getAddress(), $ad['address']);
        }

        $response = RequestBuilder::create(self::getClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/search')
            ->setJsonContent([
                'for' => AdFor::RENT
            ])
            ->getResponse();
        self::assertResponseIsSuccessful();
        $content = $response->getJsonContent();
        self::assertNotNull($content);
        foreach ($content as $ad) {
            self::assertEquals(self::$agent->getFor()->value, $ad['for']);
        }
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