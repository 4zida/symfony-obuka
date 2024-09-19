<?php

namespace App\Tests\EventListeners\Document;

use App\Document\Ad\Ad;
use App\Entity\Company;
use App\Entity\User;
use App\Tests\BaseTestController;
use App\Tests\DocumentManagerAwareTrait;
use App\Util\AdStatus;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Random\RandomException;

class AdDocumentPrePersistListenerTest extends BaseTestController
{
    use DocumentManagerAwareTrait;

    private static ?Ad $ad;
    private static ?User $user;
    private static ?Company $company;

    /**
     * @throws MongoDBException
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$company = self::createTestCompany();
        self::persistEntity(self::$company);

        self::$user = self::createTestUser(self::$company);
        self::persistEntity(self::$user);

        self::$ad = self::createTestAd(self::$company, self::$user);
        self::persistDocument(self::$ad);

        self::ensureKernelShutdown();
    }

    public function testPrePersist(): void
    {
        self::assertNotEmpty(self::$ad->getCreatedAt());
        self::assertEquals(AdStatus::HIDDEN, self::$ad->getStatus());
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws MongoDBException
     */
    public static function tearDownAfterClass(): void
    {
        self::removeDocumentById(Ad::class, self::$ad->getId());
        self::removeEntityById(User::class, self::$user->getId());
        self::removeEntityById(Company::class, self::$company->getId());

        parent::tearDownAfterClass();
    }
}