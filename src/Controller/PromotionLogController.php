<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\PromotionLog;
use App\Entity\User;
use App\Util\ContextGroup;
use Doctrine\ORM\EntityManagerInterface;
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
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    #[Route(path: '/api/promotion-log', methods: Request::METHOD_GET)]
    public function all(): JsonResponse
    {
        return $this->jsonWithGroup($this->entityManager->getRepository(PromotionLog::class)->findAll(),
            ContextGroup::ADMIN_PROMOTION_LOG);
    }

    #[Route(path: '/api/promotion-log/{user}', methods: Request::METHOD_GET)]
    public function allByUser(User $user): JsonResponse
    {
        return $this->jsonWithGroup($this->entityManager->getRepository(PromotionLog::class)->findByUser($user),
            ContextGroup::USER_PROMOTION_LOG);
    }
}
