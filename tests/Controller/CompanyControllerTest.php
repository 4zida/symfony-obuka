<?php

namespace App\Tests\Controller;

use App\Entity\Company;
use App\Tests\EntityManagerAwareTrait;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Nebkam\FluentTest\RequestBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class CompanyControllerTest extends WebTestCase
{
    use EntityManagerAwareTrait;
    private static ?Company $agent;
    private static ?int $agentId;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$agent = (new Company())
            ->setName("Test Company")
            ->setAddress("Test Address");

        self::$agentId = self::persistEntity(self::$agent);
        self::flushEntities();

        self::ensureKernelShutdown();
    }
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

    public function testCreate(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_POST)
            ->setJsonContent([
                "name" => "test",
                "address" => "address"
            ])
            ->setUri('/api/company/')
            ->getResponse();
        self::assertResponseIsSuccessful();

        dump($response->getResponse()->getContent());
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function testShow(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/company/'.self::$agentId)
            ->getResponse();
        $this->assertResponseIsSuccessful();

        $content = $response->getResponse()->getContent();
        $company = self::findEntity(Company::class, self::$agentId);
        self::assertNotEmpty($content);
        self::assertJson($content);
        self::assertEquals(self::$agentId, $company->getId());
        dump($content, $company);
    }

    /**
     * @throws ORMException
     */
    public static function tearDownAfterClass(): void
    {
        self::removeEntityById(Company::class, self::$agent->getId());

        parent::tearDownAfterClass();
    }
}
