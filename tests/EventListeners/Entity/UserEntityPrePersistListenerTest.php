<?php

namespace App\Tests\EventListeners\Entity;

use App\Entity\Company;
use App\Entity\User;
use App\Tests\BaseTestController;
use App\Tests\EntityManagerAwareTrait;
use Doctrine\ORM\Exception\ORMException;

class UserEntityPrePersistListenerTest extends BaseTestController
{
    use EntityManagerAwareTrait;
    public static ?User $user;
    public static ?Company $company;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$company = self::createTestCompany();
        self::persistEntity(self::$company);

        self::$user = self::createTestUser(self::$company);
        self::persistEntity(self::$user);

        self::ensureKernelShutdown();
    }

    public function testPrePersist(): void
    {
        self::assertNotEmpty(self::$user->getCreatedAt());
        self::assertFalse(self::$user->getIsActive());
    }

    /**
     * @throws ORMException
     */
    public static function tearDownAfterClass(): void
    {
        self::removeEntityById(User::class, self::$user->getId());
        self::removeEntityById(Company::class, self::$company->getId());

        parent::tearDownAfterClass();
    }
}