<?php

namespace App\Tests\Controller;

use App\Entity\Company;
use App\Tests\BaseTestController;
use App\Tests\EntityManagerAwareTrait;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Nebkam\FluentTest\RequestBuilder;
use Symfony\Component\HttpFoundation\Request;

class CompanyControllerTest extends BaseTestController
{
    use EntityManagerAwareTrait;
    private static ?Company $agent;
    private static ?int $agentId;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$agent = self::createTestCompany();
        self::$agentId = self::persistEntity(self::$agent);

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
    }

    public function testCreate(): void
    {
        RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_POST)
            ->setJsonContent([
                "name" => "test",
                "address" => "address"
            ])
            ->setUri('/api/company/')
            ->getResponse();
        self::assertResponseIsSuccessful();
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
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function testUpdate(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_PATCH)
            ->setUri('/api/company/'.self::$agentId)
            ->setJsonContent([
                "name" => "Test Company UPDATED",
            ])
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getResponse()->getContent();
        $company = self::findEntity(Company::class, self::$agentId);
        self::assertNotEmpty($content);
        self::assertEquals("Company updated.", $content);
        self::assertEquals(self::$agentId, $company->getId());
    }

    public function testDelete(): void
    {
        RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_DELETE)
            ->setUri('/api/company/'.self::persistEntity(self::createTestCompany()))
            ->getResponse();
        self::assertResponseIsSuccessful();
    }

    public function testFindById(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/company/search/'.self::$agentId)
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertEquals(self::$agentId, $content["id"]);
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
