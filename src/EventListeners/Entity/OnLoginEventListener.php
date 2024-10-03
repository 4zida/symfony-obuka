<?php

namespace App\EventListeners\Entity;

use App\Entity\User;
use App\Repository\UserRepository;
use Exception;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Clock\ClockAwareTrait;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

#[AsEventListener(event: LoginSuccessEvent::class, method: 'onLogin')]
readonly class OnLoginEventListener
{
    use ClockAwareTrait;
    public function __construct(
        private UserRepository  $userRepository,
        private LoggerInterface $logger,
    )
    {
    }

    public function onLogin(LoginSuccessEvent $event): void
    {
        try {
            $user = $event->getUser();
            if (!$user instanceof User) return;
            $user->setLastSeenAt($this->clock->now());
            $this->userRepository->updateUser($user);
        } catch (Exception $e) {
            $this->logger->log($e->getMessage(), LogLevel::ERROR);
        }
    }
}