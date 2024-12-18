<?php

declare(strict_types=1);

namespace App\Controller;

use App\Document\PriceStats;
use App\Util\ContextGroup;
use Doctrine\ODM\MongoDB\DocumentManager;
use Nebkam\SymfonyTraits\ControllerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class PriceStatsController extends AbstractController
{
    use ControllerTrait;

    public function __construct(
        private readonly DocumentManager $documentManager
    )
    {
    }

    #[Route('/api/price-stats/', methods: Request::METHOD_GET)]
    public function index(): JsonResponse
    {
        $priceStats = $this->documentManager->getRepository(PriceStats::class)->findAll();

        return $this->jsonWithGroup($priceStats, ContextGroup::PRICE_STATS_DETAILS);
    }

    #[Route('/api/price-stats/{placeId}/{type}', methods: Request::METHOD_GET)]
    public function show(string $placeId, string $type): JsonResponse
    {
        $priceStats = $this->documentManager->getRepository(PriceStats::class)->findOneBy(['placeId' => $placeId, 'type' => $type]);

        return $this->jsonWithGroup($priceStats, ContextGroup::PRICE_STATS_DETAILS);
    }
}
