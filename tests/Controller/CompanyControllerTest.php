<?php

namespace App\Tests\Controller;

use App\Controller\CompanyController;
use App\Entity\Company;
use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\Serializer;

class CompanyControllerTest extends KernelTestCase
{

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
        self::bootKernel([
            'environment' => 'my_test_env',
            'debug'       => false,
        ]);
        $container = self::$kernel->getContainer();

        $company = new Company();
        $company->setName('Test Company');
        $company->setAddress('Test Address');

        $companyRepository = $this->createMock(CompanyRepository::class);
        $companyRepository->expects($this->any())
            ->method('find')
            ->willReturn($company);

        $serializer = $container->get(Serializer::class);

        $result = $serializer->serialize($company, 'json');

        $this->assertJson($result);
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
