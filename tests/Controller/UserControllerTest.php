<?php

namespace App\Tests\Controller;

use App\Entity\Company;
use App\Entity\User;
use Nebkam\FluentTest\RequestBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class UserControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/user/')
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        dump($content);
    }

    public function testFindByCompany(): void
    {
        self::markTestSkipped();
//        $companies = $this->entityManager->getRepository(Company::class)->findAll();
//        $this->assertNotEmpty($companies);
//        foreach ($companies as $company)
//        {
//            $users = $this->entityManager->getRepository(User::class)->findBy(['company' => $company]);
//        }
    }
}
