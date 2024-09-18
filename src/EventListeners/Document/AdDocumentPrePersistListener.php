<?php

namespace App\EventListeners\Document;

use App\Document\Ad\Ad;
use App\Util\AdStatus;
use DateTimeImmutable;
use Doctrine\Bundle\MongoDBBundle\Attribute\AsDocumentListener;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Events;

#[AsDocumentListener(event: Events::prePersist, connection: "default")]
class AdDocumentPrePersistListener
{
    public function __invoke(LifecycleEventArgs $args): void
    {
        $ad = $args->getDocument();
        if ($ad instanceof Ad) {
            if ($ad->getCreatedAt() === null) {
                $ad->setCreatedAt(new DateTimeImmutable());
            }
            $ad->setLastUpdated($ad->getCreatedAt());
            $ad->setStatus(AdStatus::HIDDEN);
        }
    }
}