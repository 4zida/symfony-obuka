<?php

namespace App\Service;

use App\Document\Ad\Ad;
use App\Util\AdStatus;
use DateTimeImmutable;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;

readonly class AdManager
{
    public function __construct(
        private DocumentManager $documentManager
    )
    {
    }

    /**
     * @throws MongoDBException
     */
    public function activate(Ad $ad): void
    {
        $ad->setStatus(AdStatus::ACTIVE);
        $ad->setLastUpdated(new DateTimeImmutable());
        $this->documentManager->flush();
    }

    /**
     * @throws MongoDBException
     */
    public function deactivate(Ad $ad): void
    {
        $ad->setStatus(AdStatus::DELETED);
        $ad->setLastUpdated(new DateTimeImmutable());
        $this->documentManager->flush();
    }

    /**
     * @throws MongoDBException
     */
    public function remove(Ad $ad): void
    {
        $this->documentManager->remove($ad);
        $this->documentManager->flush();
    }
}