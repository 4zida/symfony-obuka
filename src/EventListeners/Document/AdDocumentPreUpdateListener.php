<?php

namespace App\EventListeners\Document;

use App\Document\Ad\Ad;
use DateTimeImmutable;
use Doctrine\Bundle\MongoDBBundle\Attribute\AsDocumentListener;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Events;

#[AsDocumentListener(event: Events::preUpdate, connection: "default")]
class AdDocumentPreUpdateListener
{
    public function __invoke(LifecycleEventArgs $args): void
    {
        $ad = $args->getDocument();
        if ($ad instanceof Ad) {
            $ad->setLastUpdated(new DateTimeImmutable());
        }
    }
}