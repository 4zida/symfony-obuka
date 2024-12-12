<?php

namespace App\Repository;

use App\Document\Ad;
use App\Entity\PromotionLog;
use App\Entity\User;
use App\Util\PremiumDuration;
use DateMalformedIntervalStringException;
use DateMalformedStringException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Clock\ClockAwareTrait;

/**
 * @extends ServiceEntityRepository<PromotionLog>
 */
class PromotionLogRepository extends ServiceEntityRepository
{
    use ClockAwareTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PromotionLog::class);
    }

    /**
     * @throws DateMalformedStringException
     * @throws DateMalformedIntervalStringException
     */
    public function start(Ad $ad, PremiumDuration $duration, ?User $promotedBy = null): int
    {
        $log = (new PromotionLog())
            ->setPromotedAt($this->now())
            ->setAdId($ad->getId())
            ->setDuration($duration->value)
            ->setShouldExpireAt($duration->toFutureDate())
            ->setPromotedBy($promotedBy?->getId());

        $this->getEntityManager()->persist($log);
        $this->getEntityManager()->flush();
        return $log->getId();
    }

    public function end(?int $id): void
    {
        $promotionLog = $this->find($id);
        $promotionLog->setExpiredAt($this->now());
        $this->getEntityManager()->flush();
    }

    /**
     * @throws DateMalformedStringException
     * @throws DateMalformedIntervalStringException
     */
    public function extend(Ad $ad, ?PremiumDuration $duration, ?User $promotedBy = null): int
    {
        $log = ($this->getEntityManager()->getRepository(PromotionLog::class)->find($ad->getPromotionLogId()))
            ->setPromotedAt($this->now())
            ->setAdId($ad->getId())
            ->setDuration($ad->getPremiumDuration() + $duration->value)
            ->setShouldExpireAt($duration->toFutureDate($ad->getPremiumExpiresAt()))
            ->setPromotedBy($promotedBy?->getId());

        $this->getEntityManager()->persist($log);
        $this->getEntityManager()->flush();
        return $log->getId();
    }
}
