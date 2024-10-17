<?php

declare(strict_types=1);

namespace App\Controller\Ad;

use App\Document\Ad;
use App\Form\PromotionRequestFormType;
use App\Model\PromotionRequest;
use App\Service\PromotionService;
use App\Util\ContextGroup;
use Doctrine\ODM\MongoDB\MongoDBException;
use Nebkam\SymfonyTraits\ControllerTrait;
use Nebkam\SymfonyTraits\FormTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class PremiumAdController extends AbstractController
{
    use ControllerTrait;
    use FormTrait;

    public function __construct(
        private readonly PromotionService $promotionService,
    )
    {
    }

    /**
     * @throws MongoDBException
     */
    #[Route(path: '/api/ad/activate_premium/{id}', name: 'activate_premium', methods: Request::METHOD_POST)]
    public function activatePremium(Request $request, Ad $ad): JsonResponse
    {
        $promotionRequest = new PromotionRequest();
        $this->handleJSONForm($request, $promotionRequest, PromotionRequestFormType::class);
        $this->promotionService->promote($ad, $promotionRequest->getDuration());

        return $this->jsonWithGroup($ad, ContextGroup::AD_COMPLETE_INFO);
    }

    /**
     * @throws MongoDBException
     */
    #[Route(path: '/api/ad/deactivate_premium/{id}', name: 'deactivate_premium', methods: Request::METHOD_GET)]
    public function deactivatePremium(Ad $ad): JsonResponse
    {
        $this->promotionService->demote($ad);

        return $this->jsonWithGroup($ad, ContextGroup::AD_COMPLETE_INFO);
    }
}
