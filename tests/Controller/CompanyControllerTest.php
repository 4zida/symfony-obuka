<?php

namespace App\Tests\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Nebkam\FluentTest\RequestBuilder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class CompanyControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/company/')
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        dump($content);
    }
}
