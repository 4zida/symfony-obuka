<?php

declare(strict_types=1);

namespace App\Controller;

use App\Document\Ad;
use App\Entity\PromotionLog;
use App\Entity\User;
use App\Exception\EmptyRepositoryException;
use App\Repository\PromotionLogRepository;
use App\Util\ContextGroup;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Nebkam\SymfonyTraits\ControllerTrait;
use Nebkam\SymfonyTraits\FormTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class PromotionLogController extends AbstractController
{
    use ControllerTrait;
    use FormTrait;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PromotionLogRepository $promotionLogRepository
    )
    {
    }

    /**
     * @throws EmptyRepositoryException
     */
    #[Route(path: '/api/promotion-log', methods: Request::METHOD_GET)]
    public function all(): JsonResponse
    {
        $promotionLogRepository = $this->entityManager->getRepository(PromotionLog::class);

        try {
            $promotionLogs = $promotionLogRepository->findAll();
        } catch (Exception $e) {
            throw new EmptyRepositoryException();
        }

        return $this->jsonWithGroup($promotionLogs, ContextGroup::ADMIN_PROMOTION_LOG);
    }

    /**
     * @throws EmptyRepositoryException
     */
    #[Route(path: '/api/promotion-log/user/{user}', methods: Request::METHOD_GET)]
    public function allByUser(User $user): JsonResponse
    {
        $promotionLogRepository = $this->entityManager->getRepository(PromotionLog::class);

        try {
            $promotionLogs = $promotionLogRepository->findBy(['adAuthorId' => $user->getId()]);
        } catch (Exception $e) {
            throw new EmptyRepositoryException();
        }

        return $this->jsonWithGroup($promotionLogs, ContextGroup::USER_PROMOTION_LOG);
    }

    /**
     * @throws EmptyRepositoryException
     */
    #[Route(path: '/api/promotion-log/ad/{ad}', methods: Request::METHOD_GET)]
    public function allForAd(Ad $ad): JsonResponse
    {
        $promotionLogRepository = $this->entityManager->getRepository(PromotionLog::class);

        try {
            $promotionLogs = $promotionLogRepository->findBy(['adId' => $ad->getId()]);

        } catch (Exception $e) {
            throw new EmptyRepositoryException();
        }

        return $this->jsonWithGroup($promotionLogs, ContextGroup::USER_PROMOTION_LOG);
    }
}
