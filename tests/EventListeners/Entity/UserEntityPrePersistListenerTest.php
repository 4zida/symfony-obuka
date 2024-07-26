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
    public static ?User $agent;
    public static ?int $agentId;
    public static ?Company $company;
    public static ?int $companyId;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$company = self::createTestCompany()->setAddress("user");
        self::$companyId = self::persistEntity(self::$company);

        self::$agent = self::createTestUser(self::$company);
        self::$agentId = self::persistEntity(self::$agent);

        self::ensureKernelShutdown();
    }

    public function testPrePersist(): void
    {
        self::assertNotEmpty(self::$agent->getCreatedAt());
        self::assertFalse(self::$agent->getIsActive());
    }

    /**
     * @throws ORMException
     */
    public static function tearDownAfterClass(): void
    {
        self::removeEntityById(User::class, self::$agent->getId());
        self::removeEntityById(Company::class, self::$company->getId());

        parent::tearDownAfterClass();
    }
}