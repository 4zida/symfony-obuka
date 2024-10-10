<?php

namespace App\EventListeners\Document;

use App\Document\Ad;
use Doctrine\Bundle\MongoDBBundle\Attribute\AsDocumentListener;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Events;
use Symfony\Component\Clock\ClockAwareTrait;

#[AsDocumentListener(event: Events::preUpdate, connection: "default")]
class AdDocumentPreUpdateListener
{
    use ClockAwareTrait;

    public function __invoke(LifecycleEventArgs $args): void
    {
        $ad = $args->getDocument();
        if ($ad instanceof Ad) {
            $ad->setLastUpdated($this->clock->now());
        }
    }
}