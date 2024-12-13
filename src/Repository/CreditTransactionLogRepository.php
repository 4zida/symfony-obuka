<?php

namespace App\Repository;

use App\Document\Ad;
use App\Entity\CreditTransactionLog;
use App\Entity\User;
use App\Util\CreditTransactionPurpose;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Clock\ClockAwareTrait;

/**
 * @extends ServiceEntityRepository<CreditTransactionLog>
 */
class CreditTransactionLogRepository extends ServiceEntityRepository
{
    use ClockAwareTrait;

    public function __construct(
        ManagerRegistry                         $registry,
        private readonly EntityManagerInterface $entityManager
    )
    {
        parent::__construct($registry, CreditTransactionLog::class);
    }

    public function add(CreditTransactionPurpose $purpose, Ad $ad, ?User $transactor = null): int
    {
        $log = (new CreditTransactionLog())
            ->setAdId($ad->getId())
            ->setPurpose($purpose)
            ->setAmount($purpose->getPrice())
            ->setTransactionDate($this->now());

        if ($transactor) {
            $log->setTransactorId($transactor->getId());
        }

        $this->entityManager->persist($log);
        $this->entityManager->flush();
        return $log->getId();
    }
}
