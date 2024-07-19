<?php

namespace App\EventListeners\Entity;

use App\Entity\Company;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Company::class)]
class CompanyEntityPrePersistListener
{
    public function prePersist(Company $company): void
    {
        $company->setCreatedAt(new DateTimeImmutable("now"));
    }
}