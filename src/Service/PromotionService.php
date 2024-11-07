<?php

namespace App\Service;

use App\Document\Ad;
use App\Entity\User;
use App\Exception\ClosedCreditBalanceException;
use App\Exception\InsufficientCreditsException;
use App\Repository\PromotionLogRepository;
use App\Util\CreditTransactionPurpose;
use App\Util\PremiumDuration;
use DateMalformedIntervalStringException;
use DateMalformedStringException;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;

readonly class PromotionService
{
    public function __construct(
        private DocumentManager $dm,
        private PromotionLogRepository $promotionLogRepository,
        private CreditManager $creditManager
    )
    {
    }

    /**
     * @throws MongoDBException
     * @throws DateMalformedStringException
     * @throws DateMalformedIntervalStringException
     * @throws ClosedCreditBalanceException
     * @throws InsufficientCreditsException
     */
    public function promote(Ad $ad, PremiumDuration $duration, ?User $user = null): void
    {
        if ($user) {
            $this->creditManager->chargePromotion($ad, CreditTransactionPurpose::PREMIUM, $user);
        }

        $ad->activatePremium($duration);
        $logId = $this->promotionLogRepository->start($ad, $duration, $user);
        $ad->setPromotionLogId($logId);

        $this->dm->flush();
    }

    /**
     * @throws MongoDBException
     */
    public function demote(Ad $ad): void
    {
        $ad->deactivatePremium();
        if (null !== $ad->getPromotionLogId())
        {
            $this->promotionLogRepository->end($ad?->getPromotionLogId());
        }

        $this->dm->flush();
    }

    /**
     * @throws ClosedCreditBalanceException
     * @throws DateMalformedStringException
     * @throws InsufficientCreditsException
     * @throws MongoDBException
     * @throws DateMalformedIntervalStringException
     */
    public function extend(Ad $ad, ?PremiumDuration $duration, ?User $originalUser = null): void
    {
        if ($originalUser) {
            $this->creditManager->chargePromotion($ad, CreditTransactionPurpose::PREMIUM, $originalUser);
        }

        $ad->extendPremium($duration);
        $logId = $this->promotionLogRepository->extend($ad, $duration, $originalUser);
        $ad->setPromotionLogId($logId);

        $this->dm->flush();
    }
}