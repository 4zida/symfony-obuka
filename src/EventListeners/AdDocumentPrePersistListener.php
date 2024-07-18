<?php

namespace App\EventListeners;

use App\Document\Ad;
use Doctrine\Bundle\MongoDBBundle\Attribute\AsDocumentListener;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Events;
use Symfony\Component\Clock\ClockAwareTrait;

#[AsDocumentListener(event: Events::prePersist, connection: "default")]
class AdDocumentPrePersistListener
{
    use ClockAwareTrait;

    public function __construct()
    {
    }

    public function __invoke(LifecycleEventArgs $args): void
    {
        $ad = $args->getDocument();
        if($ad instanceof Ad && $ad->getCreatedAt() === null) {
            $ad->setCreatedAt(new \DateTimeImmutable());
        }
    }
}