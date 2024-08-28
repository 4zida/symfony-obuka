<?php

namespace App\EventListeners\Entity;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Serializer\SerializerInterface;

#[AsEventListener(event: LoginSuccessEvent::class, method: 'onLogin')]
readonly class OnLoginEventListener
{
    public function __construct(
        public UserRepository      $userRepository,
        public SerializerInterface $serializer
    )
    {
    }

    public function onLogin(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();

        /** @var User $user */
        $user->setLastSeenAt(new \DateTimeImmutable());
        $this->userRepository->updateUser($user);
    }
}