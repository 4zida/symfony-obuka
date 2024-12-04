<?php

namespace App\Entity;

use App\Repository\CreditTransactionLogRepository;
use App\Util\ContextGroup;
use App\Util\CreditTransactionPurpose;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CreditTransactionLogRepository::class)]
#[Groups(ContextGroup::ADMIN_CREDIT_TRANSACTION_LOG)]
class CreditTransactionLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $adId = null;
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $transactorId = null;
    #[ORM\Column(type: 'date_immutable', nullable: true)]
    private ?DateTimeImmutable $transactionDate = null;
    #[ORM\Column(type: 'string', nullable: true, enumType: CreditTransactionPurpose::class)]
    private ?CreditTransactionPurpose $purpose = null;
    private ?int $amount = null;

    #[Groups([
        ContextGroup::USER_CREDIT_TRANSACTION_LOG,
    ])]
    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups([
        ContextGroup::USER_CREDIT_TRANSACTION_LOG,
    ])]
    public function getAdId(): ?string
    {
        return $this->adId;
    }

    public function setAdId(?string $adId): self
    {
        $this->adId = $adId;
        return $this;
    }

    #[Groups([
        ContextGroup::USER_CREDIT_TRANSACTION_LOG,
    ])]
    public function getTransactorId(): ?int
    {
        return $this->transactorId;
    }

    public function setTransactorId(?int $transactorId): self
    {
        $this->transactorId = $transactorId;
        return $this;
    }

    #[Groups([
        ContextGroup::USER_CREDIT_TRANSACTION_LOG,
    ])]
    public function getTransactionDate(): ?DateTimeImmutable
    {
        return $this->transactionDate;
    }

    public function setTransactionDate(?DateTimeImmutable $transactionDate): self
    {
        $this->transactionDate = $transactionDate;
        return $this;
    }

    #[Groups([
        ContextGroup::USER_CREDIT_TRANSACTION_LOG,
    ])]
    public function getPurpose(): ?CreditTransactionPurpose
    {
        return $this->purpose;
    }

    public function setPurpose(?CreditTransactionPurpose $purpose): self
    {
        $this->purpose = $purpose;
        return $this;
    }

    #[Groups([
        ContextGroup::USER_CREDIT_TRANSACTION_LOG,
    ])]
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(?int $amount): self
    {
        $this->amount = $amount;
        return $this;
    }
}
