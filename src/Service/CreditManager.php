<?php

namespace App\Service;

use App\Document\Ad;
use App\Entity\User;
use App\Exception\ClosedCreditBalanceException;
use App\Exception\InsufficientCreditsException;
use App\Util\CreditTransactionPurpose;
use Doctrine\ORM\EntityManagerInterface;

readonly class CreditManager
{
    public function __construct(
        private EntityManagerInterface $entityManager
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

        // TODO: Add credit transaction log

        $this->entityManager->flush();
    }
}