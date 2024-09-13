<?php

namespace App\Tests\Controller\Ad;

use App\Document\Ad\Ad;
use App\Document\Ad\Image;
use App\Entity\Company;
use App\Entity\Phone;
use App\Entity\User;
use App\Tests\BaseTestController;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use libphonenumber\PhoneNumberUtil;

class ImageControllerTest extends BaseTestController
{
    private static ?Ad $ad = null;
    private static ?User $user = null;
    private static ?Company $company = null;
    private static ?Phone $phone = null;

    /**
     * @throws MongoDBException
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $company = self::createTestCompany();
        self::persistEntity($company);
        $user = self::createTestUser($company);
        self::persistEntity($user);
        $ad = self::createTestAd($company, $user);
        self::persistDocument($ad);
        $phone = (new Phone())
            ->setFromPhoneNumber(PhoneNumberUtil::getInstance()->getExampleNumber(Phone::REGION_CODE))
            ->setUser($user);
        self::persistEntity($phone);

        self::$company = $company;
        self::$user = $user;
        self::$ad = $ad;
        self::$phone = $phone;

        self::ensureKernelShutdown();
    }

    /**
     * @throws MongoDBException
     * @throws Exception
     */
    public function testUpload(): void
    {
        $image = (new Image())
            ->setAd(self::$ad)
            ->setAlias("test");
        self::persistDocument(self::$ad);
        self::persistDocument($image);

        mkdir(self::getImagePath() . sprintf('/%s', self::$ad->getId()), 0775);
        $imagePath = self::getImagePath() . sprintf('/%s/%s', self::$ad->getId(), $image->getId());
        copy(__DIR__.'/../../mock_ad_image.jpg', $imagePath);
        $image->setLocation($imagePath);
        self::$ad->addImage($image);
        self::persistDocument($image);

        self::assertFileExists($image->getLocation());

        self::mockRemoveImage(self::$ad->getId(), $image->getId());
    }

    /**
     * @throws MongoDBException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public static function tearDownAfterClass(): void
    {
        self::removeDocumentById(Ad::class, self::$ad->getId());
        self::removeEntityById(Phone::class, self::$phone->getId());
        self::removeEntityById(User::class, self::$user->getId());
        self::removeEntityById(Company::class, self::$company->getId());


        parent::tearDownAfterClass();
    }
}