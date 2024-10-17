<?php

namespace App\Service;

use App\Document\Ad;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;

readonly class PromotionService
{
    public function __construct(
        private DocumentManager $dm
    )
    {
    }

    /**
     * @throws MongoDBException
     */
    public function promote(Ad $ad, int $duration): void
    {
        $ad->activatePremium($duration);
        $this->dm->flush();
    }

    /**
     * @throws MongoDBException
     */
    public function demote(Ad $ad): void
    {
        $ad->deactivatePremium();
        $this->dm->flush();
    }
}