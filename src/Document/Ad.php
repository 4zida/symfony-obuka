<?php

namespace App\Document;

use App\Repository\AdRepository;
use App\Util\ContextGroup;
use DateTimeImmutable;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JetBrains\PhpStorm\Deprecated;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[MongoDB\Document(collection: 'ads', repositoryClass: AdRepository::class)]
#[Unique(fields: 'url')]
#[Groups(ContextGroup::AD_ALL_DETAILS)]
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
    #[MongoDB\Field(type: 'bool')]
    protected ?bool $isActive;
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
    protected int|null $userId;
    #[MongoDB\Field(type: 'int')]
    protected int|null $companyId;
    #[MongoDB\Field(type: 'int')]
    #[Assert\NotBlank]
    protected ?int $m2;

    #[MongoDB\Field(type: 'date_immutable')]
    protected ?DateTimeImmutable $createdAt;

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Ad
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Ad
    {
        $this->description = $description;
        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): Ad
    {
        $this->url = $url;
        return $this;
    }

    public function getUserId(): int|null
    {
        return $this->userId;
    }

    public function setUserId(int|null $userId): Ad
    {
        $this->userId = $userId;
        return $this;
    }

    public function getCompanyId(): int|null
    {
        return $this->companyId;
    }

    public function setCompanyId(int|null $companyId): Ad
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

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt ?? null;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function getFloor(): ?int
    {
        return $this->floor;
    }

    public function setFloor(?int $floor): self
    {
        $this->floor = $floor;
        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive ?? null;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getM2(): ?int
    {
        return $this->m2;
    }

    public function setM2(?int $m2): self
    {
        $this->m2 = $m2;
        return $this;
    }
}