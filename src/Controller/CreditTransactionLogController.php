<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\CreditTransactionLog;
use App\Entity\User;
use App\Util\ContextGroup;
use Doctrine\ORM\EntityManagerInterface;
use Nebkam\SymfonyTraits\ControllerTrait;
use Nebkam\SymfonyTraits\FormTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class CreditTransactionLogController extends AbstractController
{
    use ControllerTrait;
    use FormTrait;

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    #[Route(path: '/api/credit-transaction-log', methods: Request::METHOD_GET)]
    public function all(): JsonResponse
    {
        $creditTransactionLogRepository = $this->entityManager->getRepository(CreditTransactionLog::class);
        return $this->jsonWithGroup($creditTransactionLogRepository->findAll(),
            ContextGroup::ADMIN_CREDIT_TRANSACTION_LOG);
    }

    #[Route(path: '/api/credit-transaction-log/{user}', methods: Request::METHOD_GET)]
    public function allByUser(User $user): JsonResponse
    {
        $creditTransactionLogRepository = $this->entityManager->getRepository(CreditTransactionLog::class);
        return $this->jsonWithGroup($creditTransactionLogRepository->findBy(['id' => $user->getId()]),
            ContextGroup::USER_CREDIT_TRANSACTION_LOG);
    }
}
