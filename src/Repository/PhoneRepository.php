<?php

namespace App\Repository;

use App\Entity\Phone;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Phone>
 */
class PhoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Phone::class);
    }

    public function findByUser(User $user): array
    {
        return self::getEntityManager()->getRepository(Phone::class)->findBy(['user' => $user]);
    }


}
