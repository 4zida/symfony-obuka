<?php

namespace App\Tests\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

class CompanyControllerTest extends KernelTestCase
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

    public function testGetCompaniesAction() : void
    {
        $companies = $this->entityManager
            ->getRepository(Company::class)
            ->findAll();

        $this->assertIsArray($companies);
    }

    public function testPostCompanyAction() : void
    {
        $this->markTestIncomplete(
            'Not implemented yet.',
        );
    }

    public function testDeleteCompanyAction() : void
    {
//        $company = new Company();
//        $company->setName('Test');
//        $company->setAddress('Test Address');
//
//        $repository = $this->createMock(CompanyRepository::class);
//        $request = new Request(['id' => $company->getId()]);


        $this->markTestIncomplete(
            'Not implemented yet.',
        );
    }
}