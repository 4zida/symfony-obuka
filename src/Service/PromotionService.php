<?php

namespace App\Service;

use App\Document\Ad;
use App\Entity\User;
use App\Repository\PromotionLogRepository;
use App\Util\PremiumDuration;
use DateMalformedIntervalStringException;
use DateMalformedStringException;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;

readonly class PromotionService
{
    public function __construct(
        private DocumentManager $dm,
        private PromotionLogRepository $promotionLogRepository
    )
    {
    }

    /**
     * @throws MongoDBException
     * @throws DateMalformedStringException
     * @throws DateMalformedIntervalStringException
     */
    public function promote(Ad $ad, PremiumDuration $duration, ?User $user = null): void
    {
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
        $this->promotionLogRepository->end($ad->getPromotionLogId());

        $this->dm->flush();
    }
}