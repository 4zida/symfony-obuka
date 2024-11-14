<?php

namespace App\Service;

use App\Document\Ad;
use App\Entity\User;
use App\Exception\ClosedCreditBalanceException;
use App\Exception\InsufficientCreditsException;
use App\Repository\CreditTransactionLogRepository;
use App\Util\CreditTransactionPurpose;
use Doctrine\ORM\EntityManagerInterface;

readonly class CreditManager
{
    public function __construct(
        private EntityManagerInterface         $entityManager,
        private CreditTransactionLogRepository $transactionLog
    )
    {
    }

    /**
     * @throws ClosedCreditBalanceException
     * @throws InsufficientCreditsException
     */
    public function chargePromotion(Ad $ad, CreditTransactionPurpose $purpose, User $promotedBy): void
    {
        $promotedBy->assertCanSpendCredits();

        $amount = $purpose->getPrice();
        $promotedBy->deductCredits($amount);

        $this->transactionLog->add($purpose, $ad, $promotedBy);

        $this->entityManager->flush();
    }
}