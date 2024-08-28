<?php

namespace App\EventListeners\Entity;

use App\Entity\User;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: User::class)]
class UserEntityPrePersistListener
{
    public function prePersist(User $user): void
    {
        $user->setCreatedAt(new DateTimeImmutable());
        $user->setIsActive(false);
        $user->setLastSeenAt(new DateTimeImmutable());
    }
}