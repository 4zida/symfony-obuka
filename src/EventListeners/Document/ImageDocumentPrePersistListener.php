<?php

namespace App\EventListeners\Document;

use App\Document\Ad\Image;
use DateTimeImmutable;
use Doctrine\Bundle\MongoDBBundle\Attribute\AsDocumentListener;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

#[AsDocumentListener(event: Events::prePersist, connection: 'default')]
class ImageDocumentPrePersistListener
{
    public function __invoke(LifecycleEventArgs $args): void
    {
        $image = $args->getDocument();
        if ($image instanceof Image) {
            if ($image->getCreatedAt() === null) {
                $image->setCreatedAt(new DateTimeImmutable());
            }
        }
    }
}