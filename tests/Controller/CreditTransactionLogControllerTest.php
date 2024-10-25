<?php

namespace App\Tests\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Tests\BaseTestController;
use App\Tests\EntityManagerAwareTrait;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Nebkam\FluentTest\RequestBuilder;
use Symfony\Component\HttpFoundation\Request;

class CreditTransactionLogControllerTest extends BaseTestController
{
    use EntityManagerAwareTrait;

    private static ?User $user = null;
    private static ?Company $company = null;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$company = self::createTestCompany();
        self::persistEntity(self::$company);

        self::$user = self::createTestUser(self::$company);
        self::persistEntity(self::$user);

        self::ensureKernelShutdown();
    }

    public function testAll(): void
    {
        $response = RequestBuilder::create($this->createClient())
            ->setUri('/api/credit-transaction-log')
            ->setMethod(Request::METHOD_GET)
            ->getResponse();
        self::assertResponseIsSuccessful();
    }

    public function testAllByUser(): void
    {
        $response = RequestBuilder::create($this->createClient())
            ->setUri('/api/credit-transaction-log/' . self::$user->getId())
            ->setMethod(Request::METHOD_GET)
            ->getResponse();
        self::assertResponseIsSuccessful();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public static function tearDownAfterClass(): void
    {
        self::removeEntityById(User::class, self::$user->getId());
        self::removeEntityById(Company::class, self::$company->getId());

        parent::tearDownAfterClass();
    }
}
