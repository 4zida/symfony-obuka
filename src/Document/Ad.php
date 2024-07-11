<?php

namespace App\Document;

use App\Repository\AdRepository;
use App\Util\ContextGroup;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[MongoDB\Document(collection: 'ads', repositoryClass: AdRepository::class)]
#[Unique(fields: 'url')]
class Ad
{
    #[MongoDB\Id]
    protected string $id;
    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    #[Groups(ContextGroup::AD_INFO)]
    protected string $name;
    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    #[Groups(ContextGroup::AD_INFO)]
    protected string $description;
    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    #[Groups(ContextGroup::AD_INFO)]
    protected string $url;
    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    #[Groups(ContextGroup::AD_INFO)]
    protected string $dateTime;

    #[MongoDB\Field(type: 'int')]
    #[Assert\NotBlank]
    protected int $unixTime;
    #[MongoDB\Field(type: 'int')]
    protected string $userId;
    #[MongoDB\Field(type: 'int')]
    protected string $companyId;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Ad
    {
        $this->id = $id;
        return $this;
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

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): Ad
    {
        $this->userId = $userId;
        return $this;
    }

    public function getCompanyId(): string
    {
        return $this->companyId;
    }

    public function setCompanyId(string $companyId): void
    {
        $this->companyId = $companyId;
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
}