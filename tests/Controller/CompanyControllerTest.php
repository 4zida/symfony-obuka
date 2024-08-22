<?php

namespace App\Tests\Controller;

use App\Entity\Company;
use App\Tests\BaseTestController;
use App\Tests\EntityManagerAwareTrait;
use App\Util\ResponseMessage;
use Doctrine\ORM\Exception\ORMException;
use Nebkam\FluentTest\RequestBuilder;
use Symfony\Component\HttpFoundation\Request;

class CompanyControllerTest extends BaseTestController
{
    use EntityManagerAwareTrait;
    private static ?Company $agent;
    private static ?int $agentId;
    private ?array $agentJsonData = [
        "name" => "test",
        "address" => "address"
    ];

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$agent = self::createTestCompany()->setAddress("company");
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
        self::assertNotEmpty($content);
        self::assertIsArray($content);
    }

    public function testCreate(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_POST)
            ->setJsonContent($this->agentJsonData)
            ->setUri('/api/company/')
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getRawContent();
        self::assertEquals(ResponseMessage::COMPANY_CREATED, $content);
    }

    public function testShow(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/company/'.self::$agentId)
            ->getResponse();
        $this->assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertIsArray($content);
        self::assertEquals(self::$agentId, $content['id']);
    }

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

        $content = $response->getRawContent();
        self::assertNotEmpty($content);
        self::assertEquals(ResponseMessage::COMPANY_UPDATED, $content);
    }

    public function testDelete(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_DELETE)
            ->setUri('/api/company/'.self::$agentId)
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getRawContent();
        self::assertEquals(ResponseMessage::COMPANY_DELETED, $content);
    }

    public function testFindById(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/company/search/'.self::$agentId)
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertIsArray($content);
        self::assertEquals(self::$agentId, $content["id"]);
    }

    public function testAdminIndex(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/admin/company')
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertIsArray($content);
    }

    public function testAdminShow(): void
    {
        $response = RequestBuilder::create(self::createClient())
            ->setMethod(Request::METHOD_GET)
            ->setUri('/api/admin/company/'.self::$agentId)
            ->getResponse();
        self::assertResponseIsSuccessful();

        $content = $response->getJsonContent();
        self::assertNotEmpty($content);
        self::assertEquals(self::$agentId, $content['id']);
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
