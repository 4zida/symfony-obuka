<?php

namespace App\Tests\Controller\Ad;

use App\Document\Ad\Ad;
use App\Entity\Company;
use App\Entity\Phone;
use App\Entity\User;
use App\Tests\BaseTestController;
use App\Tests\DocumentManagerAwareTrait;
use App\Tests\EntityManagerAwareTrait;
use App\Tests\ImageTestTrait;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use FilesystemIterator;
use libphonenumber\PhoneNumberUtil;
use Nebkam\FluentTest\RequestBuilder;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class ImageControllerTest extends BaseTestController
{
    use DocumentManagerAwareTrait;
    use EntityManagerAwareTrait;
    use ImageTestTrait;
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
     * @throws Exception
     */
    public function testUpload(): void
    {
        $client = static::createClient();
        $mockAdImagePath = self::getMockAdImagePath();

        // Upload file and create a copy of it by renaming it
        $file = new UploadedFile($mockAdImagePath, "mock_ad_image.jpg");
        copy($mockAdImagePath, $mockAdImagePath . ".tmp");

        RequestBuilder::create($client)
            ->setMethod(Request::METHOD_POST)
            ->setUri("/api/ad/" . self::$ad->getId() . "/images")
            ->setFiles([
                "image" => $file
            ])
            ->getResponse();
        self::assertResponseIsSuccessful();

        // Rename the copied file back to the original
        copy($mockAdImagePath . ".tmp", $mockAdImagePath);

        // Delete the temporary files
        $dir = "/home/veljko-bogdan/PhpstormProjects/symfony-obuka/tmp";
        if(file_exists($dir)){
            $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
            $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
            foreach ( $ri as $file ) {
                $file->isDir() ?  rmdir($file) : unlink($file);
            }
        }
        unlink($mockAdImagePath . ".tmp");
    }

    public function testShowAll(): void
    {
        RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/ad/images')
            ->getResponse();
        self::assertResponseIsSuccessful();
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