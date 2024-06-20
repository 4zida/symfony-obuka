<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserControllerTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;

    protected function setUp() : void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function tearDown() : void
    {
        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function testGetUserAction() : void
    {
        $users = $this->entityManager
            ->getRepository(User::class)
            ->findAll();

        $this->assertIsArray($users);
    }
}