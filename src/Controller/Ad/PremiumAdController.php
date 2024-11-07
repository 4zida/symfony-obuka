<?php

namespace App\Controller\Ad;

use App\Document\Ad;
use App\Entity\User;
use App\Exception\ClosedCreditBalanceException;
use App\Exception\InsufficientCreditsException;
use App\Form\PromotionRequestFormType;
use App\Model\PromotionRequest;
use App\Repository\PromotionLogRepository;
use App\Service\PromotionService;
use App\Util\ContextGroup;
use App\ValueResolver\OriginalUser;
use DateMalformedIntervalStringException;
use DateMalformedStringException;
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
        private readonly PromotionService $promotionService
    )
    {
    }

    /**
     * @throws DateMalformedIntervalStringException
     * @throws DateMalformedStringException
     * @throws MongoDBException
     * @throws ClosedCreditBalanceException
     * @throws InsufficientCreditsException
     */
    #[Route(path: '/api/ad/activate_premium/{id}', name: 'activate_premium', methods: Request::METHOD_POST)]
    public function activatePremium(Request $request, Ad $ad, #[OriginalUser] User $originalUser): JsonResponse
    {
        $ad->assertHasImages();
        $promotionRequest = new PromotionRequest();
        $this->handleJSONForm($request, $promotionRequest, PromotionRequestFormType::class);
        if (!$ad->getPremium()) {
            $this->promotionService->promote($ad, $promotionRequest->getDuration(), $originalUser);
        } else {
            $this->promotionService->extend($ad, $promotionRequest->getDuration(), $originalUser);
        }

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
