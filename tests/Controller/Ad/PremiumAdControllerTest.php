<?php

namespace App\Tests\Controller\Ad;

use App\Document\Ad;
use App\Document\Image;
use App\Entity\Company;
use App\Entity\PromotionLog;
use App\Entity\User;
use App\Tests\BaseTestController;
use App\Util\PremiumDuration;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Nebkam\FluentTest\RequestBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PremiumAdControllerTest extends BaseTestController
{
    private static ?Ad $ad = null;
    private static ?User $user = null;
    private static ?Company $company = null;
    private static ?Image $image = null;

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

        self::$user->setCreditBalance(1000);

        self::$ad = self::createTestAd(self::$company, self::$user);

        self::$image = self::createTestImage();
        self::persistDocument(self::$image);
        self::$ad->addImage(self::$image);

        self::persistDocument(self::$ad);

        self::flushEntities();
        self::flushDocuments();

        self::ensureKernelShutdown();
    }

    public function testActivatePremium(): void
    {
        $client = self::createClient();
        $client->loginUser(self::$user);

        $response = RequestBuilder::create($client)
            ->setUri('/api/ad/activate_premium/' . self::$ad->getId())
            ->setMethod(Request::METHOD_POST)
            ->setJsonContent([
                'duration' => PremiumDuration::DAYS_7
            ])
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertEquals(PremiumDuration::DAYS_7->value, $content['premiumDuration']);
        self::assertTrue($content['premium']);
    }

    public function testActivatePremiumSecondTime(): void
    {
        $client = self::createClient();
        $client->loginUser(self::$user);

        $response = RequestBuilder::create($client)
            ->setUri('/api/ad/activate_premium/' . self::$ad->getId())
            ->setMethod(Request::METHOD_POST)
            ->setJsonContent([
                'duration' => PremiumDuration::DAYS_7
            ])
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertEquals(PremiumDuration::DAYS_7->value * 2, $content['premiumDuration']);
        self::assertTrue($content['premium']);
    }

    public function testActivatePremiumWithInsufficientCredits(): void
    {
        $client = self::createClient();

        $user = self::getEntityManager()->getRepository(User::class)->find(self::$user->getId());
        $user->setCreditBalance(0);

        $client->loginUser($user);

        $response = RequestBuilder::create($client)
            ->setUri('/api/ad/activate_premium/' . self::$ad->getId())
            ->setMethod(Request::METHOD_POST)
            ->setJsonContent([
                'duration' => PremiumDuration::DAYS_7
            ])
            ->getResponse();
        self::assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }

    public function testActivatePremiumWithInvalidDuration(): void
    {
        $client = self::createClient();
        $client->loginUser(self::$user);

        $response = RequestBuilder::create($client)
            ->setUri('/api/ad/activate_premium/' . self::$ad->getId())
            ->setMethod(Request::METHOD_POST)
            ->setJsonContent([
                'duration' => 100
            ])
            ->getResponse();
        self::assertSame(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testActivatePremiumWithDisabledCredits(): void
    {
        $client = self::createClient();

        $user = self::getEntityManager()->getRepository(User::class)->find(self::$user->getId());
        $user->setCanSpendCredits(false);

        $client->loginUser($user);

        $response = RequestBuilder::create($client)
            ->setUri('/api/ad/activate_premium/' . self::$ad->getId())
            ->setMethod(Request::METHOD_POST)
            ->setJsonContent([
                'duration' => PremiumDuration::DAYS_7
            ])
            ->getResponse();
        self::assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }

    public function testActivatePremiumWithNoUser(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setUri('/api/ad/activate_premium/' . self::$ad->getId())
            ->setMethod(Request::METHOD_POST)
            ->setJsonContent([
                'duration' => PremiumDuration::DAYS_7
            ])
            ->getResponse();
        self::assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
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
        self::assertFalse($content['premium']);
    }

    /**
     * @throws MongoDBException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public static function tearDownAfterClass(): void
    {
        self::clearLog();
        self::removeDocumentById(Ad::class, self::$ad->getId());
        self::removeEntityById(User::class, self::$user->getId());
        self::removeEntityById(Company::class, self::$company->getId());
        self::removeDocumentById(Image::class, self::$image->getId());

        parent::tearDownAfterClass();
    }

    private static function clearLog(): void
    {
        foreach(self::getEntityManager()->getRepository(PromotionLog::class)->findAll() as $log) {
            self::getEntityManager()->remove($log);
        }
    }
}
