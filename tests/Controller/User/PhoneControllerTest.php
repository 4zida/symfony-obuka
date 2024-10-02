<?php

namespace App\Tests\Controller\User;

use App\Entity\Company;
use App\Entity\Phone;
use App\Entity\User;
use App\Tests\BaseTestController;
use App\Tests\EntityManagerAwareTrait;
use App\Util\CustomRequirement;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use libphonenumber\NumberParseException;
use Nebkam\FluentTest\RequestBuilder;
use Symfony\Component\HttpFoundation\Request;

class PhoneControllerTest extends BaseTestController
{
    use EntityManagerAwareTrait;

    public static Company $company;
    public static Phone $phone;
    public static User $user;

    /**
     * @throws NumberParseException
     */
    public static function setUpBeforeClass(): void
    {
        self::$company = self::createTestCompany();
        self::persistEntity(self::$company);

        self::$user = self::createTestUser(self::$company);
        self::persistEntity(self::$user);

        self::$phone = self::createTestPhone(self::$user);
        self::persistEntity(self::$phone);

        self::ensureKernelShutdown();
    }

    public function testIndex(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri("/api/phone/")
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        foreach ($content as $phone) {
            self::assertMatchesRegularExpression(CustomRequirement::PHONE, $phone["full"]);
        }
    }

    public function testUpdate(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_PATCH)
            ->setUri("/api/phone/" . self::$phone->getId())
            ->setJsonContent(["full" => "+381651112233"])
            ->getResponse();
        self::assertResponseIsSuccessful();
    }

    public function testCreate(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_POST)
            ->setUri("/api/phone/")
            ->setJsonContent(["full" => "+381651111111"])
            ->getResponse();
        self::assertResponseIsSuccessful();
    }

    public function testFindById(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri("/api/phone/search/" . self::$phone->getId())
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertEquals(self::$phone->getId(), $content['id']);
    }

    public function testFindByUser(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri("/api/phone/search/user/" . self::$user->getId())
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        foreach ($content as $phone) {
            self::assertEquals(self::$phone->getUser()->getId(), $phone['user']['id']);
        }
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public static function tearDownAfterClass(): void
    {
        self::removeEntityById(Phone::class, self::$phone->getId());
        self::removeEntityById(User::class, self::$user->getId());
        self::removeEntityById(Company::class, self::$company->getId());

        parent::tearDownAfterClass();
    }
}