<?php

namespace App\Tests;

use App\Document\Ad;
use App\Document\AdFor;
use App\Document\Image;
use App\Entity\Company;
use App\Entity\Phone;
use App\Entity\User;
use App\Util\UserRole;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseTestController extends WebTestCase
{
    use EntityManagerAwareTrait;
    use DocumentManagerAwareTrait;

    protected static string $testPhoneNumber = "+381651112233";
    protected static ?array $adJsonData = [
        "name" => "test",
        "description" => "test description",
        "url" => "https://symfony.com/doc/current/testing/database.html",
        "address" => "test address",
        "price" => 1000,
        "floor" => -1,
        "m2" => 50,
        "for" => AdFor::RENT
    ];

    protected static ?array $userJsonData = [
        "name" => "Test User",
        "role" => UserRole::BackEnd,
        "surname" => "Test Surname",
        "password" => "Test Password",
        "email" => "test@gmail.com",
        "company" => null
    ];

    protected static ?array $companyJsonData = [
        "name" => "test",
        "address" => "address"
    ];

    protected static function createTestCompany(): Company
    {
        return (new Company())
            ->setName("Test Company")
            ->setAddress("Test Address")
            ->setAboutUs("Test About Us")
            ->setLongitude(20.00)
            ->setLatitude(42.00);
    }

    protected static function createTestUser(Company|null $company): User
    {
        return (new User())
            ->setName("Test Company")
            ->setRole(UserRole::BackEnd)
            ->setPassword("testPassword")
            ->setSurname("Test Surname")
            ->setEmail("test@email.com")
            ->setCompany($company)
            ->setPasswordNoHash("testPassword");
    }

    protected static function createTestAd(Company|null $company, User|null $user): Ad
    {
        return (new Ad())
            ->setName("Test Ad")
            ->setUrl("test.url")
            ->setDescription("Description test")
            ->setUserId($user?->getId())
            ->setCompanyId($company?->getId())
            ->setFloor(5)
            ->setPrice(1000)
            ->setAddress("Test Address")
            ->setM2(50)
            ->setFor(AdFor::RENT);
    }

    /**
     * @throws NumberParseException
     */
    protected static function createTestPhone(User $user): Phone
    {
        return (new Phone())
            ->setFromPhoneNumber(PhoneNumberUtil::getInstance()->parse(self::$testPhoneNumber))
            ->setUser($user);
    }

    protected static function createTestImage(): Image
    {
        return (new Image())
            ->setLocation('test')
            ->setAlias('test')
            ->setHeight(1000)
            ->setWidth(1000)
            ->setSize(1000)
            ->setMimeType('image/jpeg');
    }
}