<?php

declare(strict_types=1);

namespace App\Controller\Ad;

use App\Document\Ad\Ad;
use App\Entity\Company;
use App\Entity\User;
use App\Form\AdType;
use App\Service\AdManager;
use App\Util\ContextGroup;
use App\Util\CustomRequirement;
use App\Util\ResponseMessage;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\Deprecated;
use Nebkam\SymfonyTraits\ControllerTrait;
use Nebkam\SymfonyTraits\FormTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class AdController extends AbstractController
{
    use FormTrait;
    use ControllerTrait;

    public function __construct(
        private readonly DocumentManager $documentManager,
        readonly AdManager               $adManager,
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    /**
     * @throws MongoDBException
     */
    #[Route('/api/ad/activate/{id}', requirements: ['id' => CustomRequirement::OBJECT_ID],
        methods: Request::METHOD_GET)]
    public function activate(Ad $ad): Response
    {
        $this->adManager->activate($ad);

        return $this->createOkResponse(ResponseMessage::AD_ACTIVATED);
    }

    /**
     * @throws MongoDBException
     */
    #[Route('/api/ad/deactivate/{id}', requirements: ['id' => CustomRequirement::OBJECT_ID],
        methods: Request::METHOD_GET)]
    public function deactivate(Ad $ad): Response
    {
        $this->adManager->deactivate($ad);

        return $this->createOkResponse(ResponseMessage::AD_DEACTIVATED);
    }

    #[Route('/api/ad/', methods: Request::METHOD_GET)]
    public function index(): JsonResponse
    {
        return $this->jsonWithGroup($this->documentManager->getRepository(Ad::class)->findAll(),
            ContextGroup::AD_ALL_DETAILS);
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
        $this->handleJSONForm($request, new Ad(), AdType::class);

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
        $this->adManager->remove($ad);

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

    #[Route('/api/ad/search/user/{user}', requirements: ['user' => Requirement::POSITIVE_INT],
        methods: Request::METHOD_GET)]
    public function findByUser(User $user): JsonResponse
    {
        $ads = $this->documentManager->getRepository(Ad::class)->findByUser($user);

        return $this->jsonWithGroup($ads, ContextGroup::AD_ALL_DETAILS);
    }

    #[Route('/api/ad/search/company/{company}', requirements: ['company' => Requirement::POSITIVE_INT],
        methods: Request::METHOD_GET)]
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
    #[Route('/api/ad/search/floor/{floor}', requirements: ['floor' => CustomRequirement::SIGNED_INT],
        methods: Request::METHOD_GET)]
    public function findByFloor(int $floor): JsonResponse
    {
        $ads = $this->documentManager->getRepository(Ad::class)->findByFloor($floor);

        return $this->jsonWithGroup($ads, ContextGroup::AD_ALL_DETAILS);
    }

    // #[IsGranted('ROLE_ADMIN')]
    #[Route('/api/admin/ad', methods: Request::METHOD_GET)]
    public function adminIndex(): JsonResponse
    {
        return $this->jsonWithGroup($this->documentManager->getRepository(Ad::class)->findAll(),
            ContextGroup::ADMIN_AD_SEARCH);
    }

    // #[IsGranted('ROLE_ADMIN')]
    #[Route('/api/admin/ad/{ad}', requirements: ['id' => CustomRequirement::OBJECT_ID], methods: Request::METHOD_GET)]
    public function adminShow(Ad $ad): JsonResponse
    {
        return $this->jsonWithGroup($ad, ContextGroup::ADMIN_AD_SEARCH);
    }

    #[Route('/api/ad/aggregate', methods: Request::METHOD_GET)]
    public function aggregationTest(): JsonResponse
    {
        $result = $this->documentManager->createAggregationBuilder(Ad::class)
            ->match()
                ->field('floor')
            ->group()
                ->field('id')
                ->expression('$floor')
                ->field('count')
                ->sum(1)
            ->sort('id', 'ASC')
            ->getAggregation();

        return $this->json($result);
    }

    #[Route('/api/ad/count', methods: Request::METHOD_GET)]
    public function countAds(): JsonResponse
    {
        $result = $this->documentManager->createAggregationBuilder(Ad::class)
            ->count("id")
            ->getAggregation();

        return $this->json($result);
    }

    #[Route('/api/ad/details/{id}', methods: Request::METHOD_GET)]
    public function getDetails(Ad $ad): JsonResponse
    {
        $adResult = $this->jsonWithGroup($ad, ContextGroup::AD_COMPLETE_INFO)->getContent();
        $user = $this->entityManager->getRepository(User::class)->find($ad->getUserId());
        $userResult = $this->jsonWithGroup($user, ContextGroup::USER_WITH_PHONE)->getContent();

        $adJson = json_decode($adResult, true);
        $adJson['userId'] = json_decode($userResult, true);

        return $this->json($adJson);
    }
}
