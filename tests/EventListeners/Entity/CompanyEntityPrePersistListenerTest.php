<?php

namespace App\Tests\EventListeners\Entity;

use App\Entity\Company;
use App\Tests\BaseTestController;
use App\Tests\EntityManagerAwareTrait;
use Doctrine\ORM\Exception\ORMException;

class CompanyEntityPrePersistListenerTest extends BaseTestController
{
    use EntityManagerAwareTrait;
    private static ?Company $agent;
    private static ?int $agentId;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$agent = self::createTestCompany();
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
        self::removeEntityById(Company::class, self::$agent->getId());

        parent::tearDownAfterClass();
    }
}