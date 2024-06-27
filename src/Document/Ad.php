<?php

namespace App\Document;

use App\Repository\AdRepository;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

#[MongoDB\Document(collection: 'ads', repositoryClass: AdRepository::class)]
#[Unique(fields: 'url')]
class Ad
{
    #[MongoDB\Id]
    protected string $id;
    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank()]
    protected string $name;
    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank()]
    protected string $description;
    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank()]
    protected string $url;
    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank()]
    protected string $dateTime;
    #[MongoDB\Field(type: 'int')]
    protected string $userId;
    #[MongoDB\Field(type: 'int')]

    protected string $companyId;

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
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

    public function setDateTime(string $dateTime): void
    {
        $this->dateTime = $dateTime;
    }
}