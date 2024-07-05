<?php

declare(strict_types=1);

namespace App\Controller;

use App\Document\Ad;
use App\Entity\Company;
use App\Form\AdType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\ODM\MongoDB\MongoDBException;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nebkam\SymfonyTraits\FormTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/ad', name: 'ad_api')]
class AdController extends BaseRestController
{
    use FormTrait;
    public function __construct(
        private readonly DocumentManager $documentManager,
        private readonly SerializerInterface $serializer,
    )
    {
    }

    #[Rest\Get('/', name: 'index', methods: Request::METHOD_GET)]
    public function index() : Response
    {
        $data = $this->serializeJSON($this->documentManager->getRepository(Ad::class)->findAll(), $this->serializer);

        return $this->generateOkResponse($data);
    }

    #[Rest\Get('/{id}', name: 'show', methods: Request::METHOD_GET)]
    public function show(Ad $ad) : Response
    {
        $data = $this->serializeJSON($ad, $this->serializer);

        return $this->generateOkResponse($data);
    }

    /**
     * @throws MongoDBException
     */
    #[Rest\Patch('/{id}', name: 'update', methods: Request::METHOD_PATCH)]
    public function update(Ad $ad, Request $request) : Response
    {
        $this->handleJSONForm($request, $ad, AdType::class);

        $this->documentManager->flush();

        return $this->generateOkResponse('Ad updated.');
    }

    /**
     * @throws MongoDBException
     */
    #[Rest\Post('/', name: 'create', methods: Request::METHOD_POST)]
    public function create(Request $request) : Response
    {
        $ad = new Ad();

        $this->handleJSONForm($request, $ad, AdType::class);

        $this->documentManager->flush();

        return $this->generateOkResponse('Ad created.');
    }

    /**
     * @throws MongoDBException
     */
    #[Rest\Delete('/{id}', name: 'delete', methods: Request::METHOD_DELETE)]
    public function delete(Ad $ad) : Response
    {
        $this->documentManager->remove($ad);
        $this->documentManager->flush();

        return $this->generateOkResponse('Ad deleted.');
    }

    /**
     * @throws MappingException
     * @throws LockException
     */
    #[Rest\Get('/search/{id}', name: 'search_by_id', methods: Request::METHOD_GET)]
    public function findById(int $id) : Response
    {
        $ad = $this->documentManager->getRepository(Ad::class)->find($id);

        if (null === $ad) {
            return $this->generateNotFoundResponse('Ads not found.');
        }

        $data = $this->serializeJSON($ad, $this->serializer);

        return $this->generateOkResponse($data);
    }

    #[Rest\Get('/search/user/{user}', name: 'search_by_role', methods: Request::METHOD_GET)]
    public function findByUser(string $user) : Response
    {
        $ad = $this->documentManager->getRepository(Ad::class)->findByUser($user);

        if (!$ad) {
            return $this->generateNotFoundResponse('Ads not found.');
        }

        $data = $this->serializeJSON($ad, $this->serializer);

        return $this->generateNotFoundResponse($data);
    }

    #[Rest\Get('/search/company/{company}', name: 'search_by_company', methods: Request::METHOD_GET)]
    public function findByCompany(Company $company) : Response
    {
        $ad = $this->documentManager->getRepository(Ad::class)->findByCompany($company);

        if (!$ad) {
            return $this->generateNotFoundResponse('Ads not found.');
        }

        $data = $this->serializeJSON($ad, $this->serializer);

        return $this->generateOkResponse($data);
    }
}
