<?php

namespace App\Service;

use App\Document\Ad\Ad;
use App\Util\AdStatus;
use DateTimeImmutable;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Clock\ClockAwareTrait;

readonly class AdManager
{
    use ClockAwareTrait;
    public function __construct(
        private DocumentManager    $documentManager,
        private AdImageFileManager $adImageFileManager,
        private LoggerInterface    $logger
    )
    {
    }

    /**
     * @throws MongoDBException
     */
    public function activate(Ad $ad): void
    {
        $ad->setStatus(AdStatus::ACTIVE);
        $ad->setLastUpdated($this->clock->now());
        $this->documentManager->flush();
    }

    /**
     * @throws MongoDBException
     */
    public function deactivate(Ad $ad): void
    {
        $ad->setStatus(AdStatus::DELETED);
        $ad->setLastUpdated($this->clock->now());
        $this->documentManager->flush();
    }

    /**
     * @throws MongoDBException
     */
    public function remove(Ad $ad): void
    {
        try {
            $this->adImageFileManager->removeDir($ad->getId());
        } catch (Exception $e) {
            $this->logger->warning($e->getMessage());
        }
        $this->documentManager->remove($ad);
        $this->documentManager->flush();
    }
}