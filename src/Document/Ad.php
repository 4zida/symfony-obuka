<?php

namespace App\Document;

use App\Form\AdType;
use App\Repository\AdRepository;
use App\Util\AdStatus;
use App\Util\ContextGroup;
use DateTimeImmutable;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JetBrains\PhpStorm\Deprecated;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[MongoDB\Document(collection: 'ads', repositoryClass: AdRepository::class)]
#[Unique(fields: 'url')]
#[Groups(ContextGroup::ADMIN_AD_SEARCH)]
class Ad
{
    #[MongoDB\Field(type: 'string')]
    #[MongoDB\Id]
    protected ?string $id;
    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    protected ?string $name;
    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    protected ?string $description;
    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    protected ?string $address;
    #[MongoDB\Field(type: 'int')]
    #[Assert\NotBlank]
    protected ?int $floor;
    #[MongoDB\Field(type: 'string', enumType: AdStatus::class)]
    protected ?AdStatus $status;
    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    protected ?string $url;
    #[MongoDB\Field(type: 'string')]
    #[Deprecated]
    protected ?string $dateTime;
    #[MongoDB\Field(type: 'int')]
    #[Deprecated]
    protected ?int $unixTime;
    #[MongoDB\Field(type: 'int')]
    protected ?int $userId;
    #[MongoDB\Field(type: 'int')]
    protected ?int $companyId;
    #[MongoDB\Field(type: 'int')]
    #[Assert\NotBlank]
    protected ?int $m2;
    #[MongoDB\Field(type: 'date_immutable')]
    protected ?DateTimeImmutable $createdAt;
    #[MongoDB\Field(type: 'date_immutable')]
    protected ?DateTimeImmutable $lastUpdated;
    #[MongoDB\Field(type: 'string', enumType: AdFor::class)]
    #[Assert\NotBlank]
    protected ?AdFor $for;

    #[Groups([
        ContextGroup::AD_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::AD_COMPLETE_INFO,
    ])]
    public function getFor(): ?AdFor
    {
        return $this->for;
    }

    public function setFor(?AdFor $for): Ad
    {
        $this->for = $for;
        return $this;
    }

    #[Groups([
        ContextGroup::AD_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::AD_COMPLETE_INFO,
    ])]
    public function getId(): string
    {
        return $this->id;
    }

    #[Groups([
        ContextGroup::AD_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::AD_COMPLETE_INFO,
    ])]
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Ad
    {
        $this->name = $name;
        return $this;
    }

    #[Groups([
        ContextGroup::AD_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::AD_COMPLETE_INFO,
    ])]
    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Ad
    {
        $this->description = $description;
        return $this;
    }

    #[Groups([
        ContextGroup::AD_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::AD_COMPLETE_INFO,
    ])]
    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): Ad
    {
        $this->url = $url;
        return $this;
    }

    #[Groups([
        ContextGroup::AD_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::AD_COMPLETE_INFO
    ])]
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): Ad
    {
        $this->userId = $userId;
        return $this;
    }

    #[Groups([
        ContextGroup::AD_ALL_DETAILS,
        ContextGroup::SEARCH
    ])]
    public function getCompanyId(): ?int
    {
        return $this->companyId;
    }

    public function setCompanyId(?int $companyId): Ad
    {
        $this->companyId = $companyId;
        return $this;
    }

    public function getDateTime(): string
    {
        return $this->dateTime;
    }

    public function setDateTime(string $dateTime): Ad
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    public function getUnixTime(): int
    {
        return $this->unixTime;
    }

    public function setUnixTime(int $unixTime): Ad
    {
        $this->unixTime = $unixTime;
        return $this;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    #[Groups([
        ContextGroup::AD_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::AD_COMPLETE_INFO,
    ])]
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt ?? null;
    }

    #[Groups([
        ContextGroup::AD_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::AD_COMPLETE_INFO,
    ])]
    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;
        return $this;
    }

    #[Groups([
        ContextGroup::AD_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::AD_COMPLETE_INFO,
    ])]
    public function getFloor(): ?int
    {
        return $this->floor;
    }

    public function setFloor(?int $floor): self
    {
        $this->floor = $floor;
        return $this;
    }

    #[Groups([
        ContextGroup::AD_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::AD_COMPLETE_INFO,
    ])]
    public function getStatus(): ?AdStatus
    {
        return $this->status ?? null;
    }

    public function setStatus(?AdStatus $status): self
    {
        $this->status = $status;
        return $this;
    }

    #[Groups([
        ContextGroup::AD_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::AD_COMPLETE_INFO,
    ])]
    public function getM2(): ?int
    {
        return $this->m2;
    }

    public function setM2(?int $m2): self
    {
        $this->m2 = $m2;
        return $this;
    }

    #[Groups([
        ContextGroup::AD_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::AD_COMPLETE_INFO,
    ])]
    public function getLastUpdated(): ?DateTimeImmutable
    {
        return $this->lastUpdated;
    }

    public function setLastUpdated(?DateTimeImmutable $lastUpdated): Ad
    {
        $this->lastUpdated = $lastUpdated;
        return $this;
    }
}