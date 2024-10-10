<?php

namespace App\EventListeners\Document;

use App\Document\Image;
use Doctrine\Bundle\MongoDBBundle\Attribute\AsDocumentListener;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Clock\ClockAwareTrait;

#[AsDocumentListener(event: Events::prePersist, connection: 'default')]
class ImageDocumentPrePersistListener
{
    use ClockAwareTrait;

    public function __invoke(LifecycleEventArgs $args): void
    {
        $image = $args->getDocument();
        if ($image instanceof Image) {
            if ($image->getCreatedAt() === null) {
                $image->setCreatedAt($this->clock->now());
            }
        }
    }
}