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

    public function testCreate(): void
    {
        $this->markTestIncomplete();
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_POST)
            ->setUri('/api/user/')
            ->setJsonContent([
                "name" => "test",
                "email" => "test@example.com",
                "password" => "password",
                "surname" => "test",
                "role" => "testrole",
                "roles" => [],
            ])
            ->getResponse();
        self::assertResponseIsSuccessful();

        dump($response->getResponse()->getContent());
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
