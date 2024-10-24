<?php

namespace App\Entity;

use App\Repository\PromotionLogRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PromotionLogRepository::class)]
class PromotionLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'adAuthorId', nullable: true)]
    private ?int $adAuthorId = null;

    #[ORM\Column(name: 'adId', nullable: true)]
    private ?string $adId = null;

    #[ORM\Column(name: 'duration', nullable: true)]
    private ?int $duration = null;

    #[ORM\Column(name: 'expiredAt', nullable: true)]
    private ?DateTimeImmutable $expiredAt = null;

    #[ORM\Column(name: 'shouldExpireAt', nullable: true)]
    private ?DateTimeImmutable $shouldExpireAt = null;

    #[ORM\Column(name: 'promotedAt', nullable: true)]
    private ?DateTimeImmutable $promotedAt = null;

    #[ORM\Column(name: 'promotedBy', nullable: true)]
    private ?int $promotedBy = null;

    #[ORM\Column(name: 'demotedAt', nullable: true)]
    private ?int $demotedBy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdAuthorId(): ?int
    {
        return $this->adAuthorId;
    }

    public function setAdAuthorId(?int $adAuthorId): PromotionLog
    {
        $this->adAuthorId = $adAuthorId;
        return $this;
    }

    public function getAdId(): ?int
    {
        return $this->adId;
    }

    public function setAdId(?string $adId): PromotionLog
    {
        $this->adId = $adId;
        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): PromotionLog
    {
        $this->duration = $duration;
        return $this;
    }

    public function getExpiredAt(): ?DateTimeImmutable
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(?DateTimeImmutable $expiredAt): PromotionLog
    {
        $this->expiredAt = $expiredAt;
        return $this;
    }

    public function getShouldExpireAt(): ?DateTimeImmutable
    {
        return $this->shouldExpireAt;
    }

    public function setShouldExpireAt(?DateTimeImmutable $shouldExpireAt): PromotionLog
    {
        $this->shouldExpireAt = $shouldExpireAt;
        return $this;
    }

    public function getPromotedAt(): ?DateTimeImmutable
    {
        return $this->promotedAt;
    }

    public function setPromotedAt(?DateTimeImmutable $promotedAt): PromotionLog
    {
        $this->promotedAt = $promotedAt;
        return $this;
    }

    public function getPromotedBy(): ?int
    {
        return $this->promotedBy;
    }

    public function setPromotedBy(?int $promotedBy): PromotionLog
    {
        $this->promotedBy = $promotedBy;
        return $this;
    }

    public function getDemotedBy(): ?int
    {
        return $this->demotedBy;
    }

    public function setDemotedBy(?int $demotedBy): PromotionLog
    {
        $this->demotedBy = $demotedBy;
        return $this;
    }
}
