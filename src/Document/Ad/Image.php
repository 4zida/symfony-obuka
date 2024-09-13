<?php

namespace App\Document\Ad;

use App\Repository\ImageRepository;
use App\Util\ContextGroup;
use DateTimeImmutable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[MongoDB\Document(collection: 'image', repositoryClass: ImageRepository::class)]
#[Groups(ContextGroup::IMAGE_DETAILS)]
class Image
{
    #[MongoDB\Field(type: 'string')]
    #[MongoDB\Id]
    protected ?string $id;
    #[MongoDB\Field(type: 'string')]
    protected ?string $alias;
    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    protected ?string $location;
    #[MongoDB\Field(type: 'date_immutable')]
    protected ?DateTimeImmutable $createdAt;
    #[MongoDB\ReferenceOne(targetDocument: Ad::class, orphanRemoval: true)]
    protected ?Ad $ad;

    #[Groups([
        ContextGroup::IMAGE_DETAILS,
    ])]
    public function getId(): string
    {
        return $this->id;
    }

    #[Groups([
        ContextGroup::IMAGE_DETAILS,
    ])]
    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;
        return $this;
    }

    #[Groups([
        ContextGroup::IMAGE_DETAILS,
    ])]
    public function getAlias(): string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;
        return $this;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    #[Groups([
        ContextGroup::IMAGE_DETAILS,
    ])]
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt ?? null;
    }

    public function setAd(Ad $ad): self
    {
        $this->ad = $ad;
        return $this;
    }

    #[Groups([
        ContextGroup::IMAGE_DETAILS,
    ])]
    public function getAd(): ?Ad
    {
        return $this->ad;
    }
}