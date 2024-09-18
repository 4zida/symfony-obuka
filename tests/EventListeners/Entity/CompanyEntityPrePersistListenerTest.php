<?php

namespace App\Tests\EventListeners\Entity;

use App\Entity\Company;
use App\Tests\BaseTestController;
use App\Tests\EntityManagerAwareTrait;
use Doctrine\ORM\Exception\ORMException;

class CompanyEntityPrePersistListenerTest extends BaseTestController
{
    use EntityManagerAwareTrait;
    private static ?Company $company;
    private static ?int $agentId;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$company = self::createTestCompany();
        self::persistEntity(self::$company);

        self::ensureKernelShutdown();
    }

    public function testPrePersist(): void
    {
        self::assertNotEmpty(self::$company->getCreatedAt());
        self::assertFalse(self::$company->getIsActive());
    }

    /**
     * @throws ORMException
     */
    public static function tearDownAfterClass(): void
    {
        self::removeEntityById(Company::class, self::$company->getId());

        parent::tearDownAfterClass();
    }
}