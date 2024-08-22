<?php

declare(strict_types=1);

namespace App\Controller;

use App\Document\Ad;
use App\Entity\Company;
use App\Entity\User;
use App\Form\AdType;
use App\Util\ContextGroup;
use App\Util\CustomRequirement;
use App\Util\ResponseMessage;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\ODM\MongoDB\MongoDBException;
use JetBrains\PhpStorm\Deprecated;
use Nebkam\SymfonyTraits\ControllerTrait;
use Nebkam\SymfonyTraits\FormTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdController extends AbstractController
{
    use FormTrait;
    use ControllerTrait;

    public function __construct(
        private readonly DocumentManager $documentManager,
    )
    {
    }

    #[Route('/api/ad/', methods: Request::METHOD_GET)]
    public function index(): JsonResponse
    {
        return $this->jsonWithGroup($this->documentManager->getRepository(Ad::class)->findAll(), ContextGroup::AD_ALL_DETAILS);
    }

    #[Route('/api/ad/{id}', requirements: ['id' => CustomRequirement::OBJECT_ID], methods: Request::METHOD_GET)]
    public function show(Ad $ad): JsonResponse
    {
        return $this->jsonWithGroup($ad, ContextGroup::AD_ALL_DETAILS);
    }

    /**
     * @throws MongoDBException
     */
    #[Route('/api/ad/{id}', requirements: ['id' => CustomRequirement::OBJECT_ID], methods: Request::METHOD_PATCH)]
    public function update(Ad $ad, Request $request): Response
    {
        $this->handleJSONForm($request, $ad, AdType::class, [], false);

        $this->documentManager->flush();

        return $this->createOkResponse(ResponseMessage::AD_UPDATED);
    }

    /**
     * @throws MongoDBException
     */
    #[Route('/api/ad/', methods: Request::METHOD_POST)]
    public function create(Request $request): Response
    {
        $ad = new Ad();
        $this->handleJSONForm($request, $ad, AdType::class);

        $this->documentManager->flush();

        return $this->createOkResponse(ResponseMessage::AD_CREATED);
    }

    /**
     * @param Ad $ad
     * @return Response
     * @throws MongoDBException
     */
    #[Route('/api/ad/{id}', requirements: ['id' => CustomRequirement::OBJECT_ID], methods: Request::METHOD_DELETE)]
    public function delete(Ad $ad): Response
    {
        $ad->setIsActive(false);
        $this->documentManager->flush();

        return $this->createOkResponse(ResponseMessage::AD_DELETED);
    }

    /**
     * @throws MappingException
     * @throws LockException
     */
    #[Route('/api/ad/search/{id}', requirements: ['id' => CustomRequirement::OBJECT_ID], methods: Request::METHOD_GET)]
    public function findById(string $id): JsonResponse
    {
        $ad = $this->documentManager->getRepository(Ad::class)->find($id);

        return $this->jsonWithGroup($ad, ContextGroup::AD_ALL_DETAILS);
    }

    #[Route('/api/ad/search/user/{user}', requirements: ['user' => Requirement::POSITIVE_INT], methods: Request::METHOD_GET)]
    public function findByUser(User $user): JsonResponse
    {
        $ads = $this->documentManager->getRepository(Ad::class)->findByUser($user);

        return $this->jsonWithGroup($ads, ContextGroup::AD_ALL_DETAILS);
    }

    #[Route('/api/ad/search/company/{company}', requirements: ['company' => Requirement::POSITIVE_INT], methods: Request::METHOD_GET)]
    public function findByCompany(Company $company): JsonResponse
    {
        $ads = $this->documentManager->getRepository(Ad::class)->findByCompany($company);

        return $this->jsonWithGroup($ads, ContextGroup::AD_ALL_DETAILS);
    }

    #[Deprecated]
    #[Route('/api/ad/search/address/{address}', methods: Request::METHOD_GET)]
    public function findByAddress(string $address): JsonResponse
    {
        $ads = $this->documentManager->getRepository(Ad::class)->findByAddress($address);

        return $this->jsonWithGroup($ads, ContextGroup::AD_ALL_DETAILS);
    }

    #[Deprecated]
    #[Route('/api/ad/search/floor/{floor}', requirements: ['floor' => CustomRequirement::SIGNED_INT], methods: Request::METHOD_GET)]
    public function findByFloor(int $floor): JsonResponse
    {
        $ads = $this->documentManager->getRepository(Ad::class)->findByFloor($floor);

        return $this->jsonWithGroup($ads, ContextGroup::AD_ALL_DETAILS);
    }

    // #[IsGranted('ROLE_ADMIN')]
    #[Route('/api/admin/ad', methods: Request::METHOD_GET)]
    public function adminIndex() : JsonResponse
    {
        return $this->jsonWithGroup($this->documentManager->getRepository(Ad::class)->findAll(), ContextGroup::ADMIN_AD_SEARCH);
    }

    // #[IsGranted('ROLE_ADMIN')]
    #[Route('/api/admin/ad/{ad}', requirements: ['id' => CustomRequirement::OBJECT_ID], methods: Request::METHOD_GET)]
    public function adminShow(Ad $ad) : JsonResponse
    {
        return $this->jsonWithGroup($ad, ContextGroup::ADMIN_AD_SEARCH);
    }
}
