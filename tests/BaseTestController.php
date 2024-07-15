<?php

namespace App\Tests;

use App\Document\Ad;
use App\Entity\Company;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseTestController extends WebTestCase
{
    use EntityManagerAwareTrait;
    use DocumentManagerAwareTrait;

    protected static function createTestCompany(): Company
    {
        return (new Company())
            ->setName("Test Company")
            ->setAddress("Test Address");
    }

    protected static function createTestUser(Company $company): User
    {
        return (new User())
            ->setName("Test Agent")
            ->setRole("Test Role")
            ->setPassword("testPassword")
            ->setRoles([])
            ->setSurname("Test Surname")
            ->setEmail("test@email.com")
            ->setCompany($company)
            ->setPasswordNoHash("testPassword");
    }

    protected static function createTestAd(Company $company, User $user): Ad
    {
        return (new Ad())
            ->setName("AdTest")
            ->setUrl("test.url")
            ->setDescription("Description test")
            ->setDateTime(date("Y-m-d H:i:s"))
            ->setUnixTime(time())
            ->setUserId($user->getId())
            ->setCompanyId($company->getId());
    }
}