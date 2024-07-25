<?php

namespace App\Tests;

use App\Document\Ad;
use App\Entity\Company;
use App\Entity\User;
use App\Util\UserRole;
use Random\RandomException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseTestController extends WebTestCase
{
    use EntityManagerAwareTrait;
    use DocumentManagerAwareTrait;

    protected static function createTestCompany(): Company
    {
        return (new Company())
            ->setName("Test Company")
            ->setAddress("Test Address")
            ;
    }

    protected static function createTestUser(Company|null $company): User
    {
        return (new User())
            ->setName("Test Agent")
            ->setRole(UserRole::BackEnd)
            ->setPassword("testPassword")
            ->setSurname("Test Surname")
            ->setEmail("test@email.com")
            ->setCompany($company)
            ->setPasswordNoHash("testPassword")
            ;
    }

    /**
     * @throws RandomException
     */
    protected static function createTestAd(Company|null $company, User|null $user): Ad
    {
        return (new Ad())
            ->setName("AdTest")
            ->setUrl("test.url")
            ->setDescription("Description test")
            ->setUserId($user?->getId())
            ->setCompanyId($company?->getId())
            ->setFloor(random_int(1, 100))
            ->setAddress("Test Address")
            ->setM2(50);
    }
}