<?php

namespace App\Service;

use App\Document\Ad;
use App\Entity\User;
use App\Util\CreditTransactionPurpose;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class CreditManager
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    /**
     * @throws Exception
     */
    public function chargePromotion(Ad $ad, CreditTransactionPurpose $purpose, User $promotedBy)
    {
        $promotedBy->assertCanSpendCredits();

        $amount = $purpose->getPrice();
        $promotedBy->deductCredits($amount);

        // TODO: Add transaction log

        $this->entityManager->flush();
    }
}