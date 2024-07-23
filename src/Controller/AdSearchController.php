<?php

namespace App\Controller;

use App\Form\AdSearchFilterFormType;
use App\Search\Filter\AdFilter;
use App\Service\AdSearchService;
use App\Util\ContextGroup;
use Doctrine\ODM\MongoDB\MongoDBException;
use Nebkam\SymfonyTraits\ControllerTrait;
use Nebkam\SymfonyTraits\FormTrait;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class AdSearchController extends AbstractController
{
    use FormTrait;
    use ControllerTrait;

    /**
     * @throws ReflectionException
     * @throws MongoDBException
     */
    #[Route('/api/ad/search', name: 'ad_search', methods: Request::METHOD_GET)]
    public function search(Request $request, AdSearchService $adSearchService): JsonResponse
    {
        $filter = new AdFilter();
        $this->handleJSONForm($request, $filter, AdSearchFilterFormType::class);

        return $this->jsonWithGroup($adSearchService->search($filter), ContextGroup::AD_DETAILS);
    }
}