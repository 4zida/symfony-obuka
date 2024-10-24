<?php

namespace App\Tests\Controller\Ad;

use App\Document\Ad;
use App\Entity\Company;
use App\Entity\User;
use App\Tests\BaseTestController;
use App\Util\PremiumDuration;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Nebkam\FluentTest\RequestBuilder;
use Symfony\Component\HttpFoundation\Request;

class PremiumAdControllerTest extends BaseTestController
{
    private static ?Ad $ad = null;
    private static ?User $user = null;
    private static ?Company $company = null;

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

    public function testActivatePremium(): void
    {
        $response = RequestBuilder::create($this->createClient())
            ->setUri('/api/ad/activate_premium/' . self::$ad->getId())
            ->setMethod(Request::METHOD_POST)
            ->setJsonContent([
                'duration' => PremiumDuration::DAYS_7
            ])
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertEquals(PremiumDuration::DAYS_7->value, $content['premiumDuration']);
        self::assertTrue($content['isPremium']);
    }

    public function testDeactivatePremium(): void
    {
        $response = RequestBuilder::create($this->createClient())
            ->setUri('/api/ad/deactivate_premium/' . self::$ad->getId())
            ->setMethod(Request::METHOD_GET)
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertEquals(null, $content['premiumDuration']);
        self::assertFalse($content['isPremium']);
    }

    /**
     * @throws MongoDBException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public static function tearDownAfterClass(): void
    {
        self::removeDocumentById(Ad::class, self::$ad->getId());
        self::removeEntityById(User::class, self::$user->getId());
        self::removeEntityById(Company::class, self::$company->getId());

        parent::tearDownAfterClass();
    }
}
