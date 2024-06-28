<?php

namespace App\Tests\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CompanyControllerTest extends KernelTestCase
{
    public function setUp(): void
    {
        $kernel = static::bootKernel();
    }

    public function testFindById()
    {
        $this->markTestIncomplete();
    }

    public function testUpdate()
    {
        $this->markTestIncomplete();
    }

    public function testIndex()
    {
        $company = new Company();
        $company->setName('Test Company');
        $company->setAddress('Test Address');

        $companyRepository = $this->createMock(CompanyRepository::class);
        $companyRepository->expects($this->any())
            ->method('find')
            ->willReturn($company);

        $this->assertSame($company, $companyRepository->find($company->getId()));
    }

    public function test__construct()
    {
        $this->markTestIncomplete();
    }

    public function testDelete()
    {
        $this->markTestIncomplete();
    }

    public function testShow()
    {
        $this->markTestIncomplete();
    }

    public function testCreate()
    {
        $this->markTestIncomplete();
    }
}
