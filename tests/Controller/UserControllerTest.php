<?php

namespace App\Tests\Controller;

use App\Entity\Company;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserControllerTest extends KernelTestCase
{
    private ?EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = static::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testIndex(): void
    {
        $users = $this->entityManager
            ->getRepository(User::class)
            ->findAll();

        $this->assertNotEmpty($users);
        $this->assertIsArray($users);

        foreach ($users as $user)
        {
            $this->assertInstanceOf(User::class, $user);
        }
    }

    public function testUpdate(): void
    {
        $this->markTestIncomplete();
    }

    public function testFindByCompany(): void
    {
        $companies = $this->entityManager->getRepository(Company::class)->findAll();
        $this->assertNotEmpty($companies);
        foreach ($companies as $company)
        {
            $users = $this->entityManager->getRepository(User::class)->findBy(['company' => $company]);
        }
    }

    public function testDelete(): void
    {
        $this->markTestIncomplete();
    }

    public function testCreate(): void
    {
        $this->markTestIncomplete();
    }

    public function testFindById(): void
    {
        $this->markTestIncomplete();
    }

    public function testFindByRole(): void
    {
        $this->markTestIncomplete();
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
    }
}
