<?php

namespace App\Tests\EventListeners\Document;

use App\Document\Ad;
use App\Entity\Company;
use App\Entity\User;
use App\Tests\BaseTestController;
use App\Tests\DocumentManagerAwareTrait;
use DateTimeImmutable;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Nebkam\FluentTest\RequestBuilder;
use Symfony\Component\HttpFoundation\Request;

class AdDocumentPreUpdateListenerTest extends BaseTestController
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

    /**
     * @throws MappingException
     * @throws LockException
     */
    public function testPreUpdate()
    {
        $date = new DateTimeImmutable("-1 day");
        self::$ad->setLastUpdated($date);
        RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_PATCH)
            ->setUri('/api/ad/' . self::$ad->getId())
            ->setJsonContent(self::$adJsonData)
            ->getResponse();
        self::assertResponseIsSuccessful();
        $updatedAd = self::getDocumentManager()->getRepository(Ad::class)->find(self::$ad->getId());
        self::assertNotEquals($date, $updatedAd->getLastUpdated());
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
