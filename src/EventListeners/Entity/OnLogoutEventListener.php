<?php

namespace App\EventListeners\Entity;

use App\Entity\User;
use App\Repository\UserRepository;
use Exception;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Clock\ClockAwareTrait;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\LogoutEvent;

#[AsEventListener(event: LogoutEvent::class, method: 'onLogout')]
readonly class OnLogoutEventListener
{
    use ClockAwareTrait;

    public function __construct(
        private UserRepository  $userRepository,
        private LoggerInterface $logger
    )
    {
    }

    public function onLogout(LogoutEvent $event): void
    {
        try {
            $user = $event->getToken()->getUser();
            if (!$user instanceof User) return;
            $user->setLastSeenAt($this->clock->now());
            $this->userRepository->updateUser($user);
        } catch (Exception $e) {
            $this->logger->log($e->getMessage(), LogLevel::ERROR);
        }
    }
}