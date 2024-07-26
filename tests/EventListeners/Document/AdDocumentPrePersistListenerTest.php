<?php

namespace App\Tests\EventListeners\Document;

use App\Document\Ad;
use App\Entity\Company;
use App\Entity\User;
use App\Tests\BaseTestController;
use App\Tests\DocumentManagerAwareTrait;
use DateTimeZone;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Random\RandomException;

class AdDocumentPrePersistListenerTest extends BaseTestController
{
    use DocumentManagerAwareTrait;
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

        self::$company = self::createTestCompany()->setAddress("ad");
        self::$companyId = self::persistEntity(self::$company);

        self::$user = self::createTestUser(self::$company);
        self::$userId = self::persistEntity(self::$user);

        self::$agent = self::createTestAd(self::$company, self::$user);
        self::$agentId = self::persistDocument(self::$agent);

        self::ensureKernelShutdown();
    }

    public function testPrePersist(): void
    {
        self::assertNotEmpty(self::$agent->getCreatedAt());
        dump(self::$agent->getCreatedAt()->setTimezone(new DateTimeZone("GMT+2")));
        self::assertFalse(self::$agent->getIsActive());
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws MongoDBException
     */
    public static function tearDownAfterClass(): void
    {
        self::removeDocumentById(Ad::class, self::$agentId);
        self::removeEntityById(User::class, self::$userId);
        self::removeEntityById(Company::class, self::$companyId);

        parent::tearDownAfterClass();
    }
}