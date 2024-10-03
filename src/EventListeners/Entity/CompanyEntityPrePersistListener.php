<?php

namespace App\EventListeners\Entity;

use App\Entity\Company;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\Clock\ClockAwareTrait;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Company::class)]
class CompanyEntityPrePersistListener
{
    use ClockAwareTrait;
    public function prePersist(Company $company): void
    {
        $company->setCreatedAt($this->clock->now());
        $company->setIsActive(false);
    }
}