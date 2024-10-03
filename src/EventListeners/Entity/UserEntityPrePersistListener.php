<?php

namespace App\EventListeners\Entity;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\Clock\ClockAwareTrait;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: User::class)]
class UserEntityPrePersistListener
{
    use ClockAwareTrait;
    public function prePersist(User $user): void
    {
        $user->setCreatedAt($this->clock->now());
        $user->setIsActive(false);
        $user->setLastSeenAt($this->clock->now());
    }
}