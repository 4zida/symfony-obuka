<?php

namespace App\EventListeners\Entity;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Serializer\SerializerInterface;

#[AsEventListener(event: LogoutEvent::class, method: 'onLogout')]
readonly class OnLogoutEventListener
{
    public function __construct(
        public UserRepository      $userRepository,
        public SerializerInterface $serializer
    )
    {
    }

    public function onLogout(LogoutEvent $event): void
    {
        $user = $event->getToken()->getUser();
        if (!$user instanceof User) return;

        $user->setLastSeenAt(new \DateTimeImmutable());
        $this->userRepository->updateUser($user);
    }
}