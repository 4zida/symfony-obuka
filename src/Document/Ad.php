<?php

namespace App\Document;

use App\EventListeners\Document\AdDocumentPrePersistListener;
use App\EventListeners\Document\AdDocumentPreUpdateListener;
use App\Repository\AdRepository;
use App\Util\AdStatus;
use App\Util\ContextGroup;
use App\Util\PremiumDuration;
use DateMalformedStringException;
use DateTimeImmutable;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JetBrains\PhpStorm\Deprecated;
use Symfony\Component\Clock\ClockAwareTrait;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[MongoDB\Document(collection: 'ads', repositoryClass: AdRepository::class)]
#[Unique(fields: 'url')]
#[Groups(ContextGroup::ADMIN_AD_SEARCH)]
class Ad
{
    use ClockAwareTrait;

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
    #[Assert\GreaterThanOrEqual(value: -2, message: 'Floors below -2 not supported.')]
    #[Assert\LessThanOrEqual(value: 50, message: 'Floors above 50 not supported.')]
    protected ?int $floor;
    /**
     * @see AdDocumentPrePersistListener
     */
    #[MongoDB\Field(type: 'string', enumType: AdStatus::class)]
    protected ?AdStatus $status;
    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    #[Assert\Url(requireTld: true)]
    protected ?string $url;
    #[Deprecated]
    #[MongoDB\Field(type: 'string')]
    protected ?string $dateTime;
    #[Deprecated]
    #[MongoDB\Field(type: 'int')]
    protected ?int $unixTime;
    #[MongoDB\Field(type: 'int')]
    protected ?int $userId;
    #[MongoDB\Field(type: 'int')]
    protected ?int $companyId;
    #[MongoDB\Field(type: 'float')]
    #[Assert\GreaterThan(value: 0, message: 'The square footage should be a positive number.')]
    #[Assert\NotBlank]
    protected ?int $m2;
    #[MongoDB\Field(type: 'int')]
    #[Assert\GreaterThanOrEqual(value: 0, message: 'The price should be a positive number.')]
    protected ?int $price;

    /**
     * @see AdDocumentPrePersistListener
     */
    protected ?DateTimeImmutable $createdAt;

    /**
     * @see AdDocumentPreUpdateListener
     * @see AdDocumentPrePersistListener
     */
    protected ?DateTimeImmutable $lastUpdated;
    #[MongoDB\Field(type: 'string', enumType: AdFor::class)]
    #[Assert\NotBlank]
    protected ?AdFor $for;
    #[MongoDB\ReferenceMany(nullable: true, targetDocument: Image::class, mappedBy: Ad::class)]
    protected ?ArrayCollection $images = null;
    #[MongoDB\Field(type: 'integer')]
    protected ?int $premiumDuration = null;
    #[MongoDB\Field(type: 'date_immutable')]
    protected ?DateTimeImmutable $premiumExpiresAt = null;

    #[MongoDB\Field(type: 'integer')]
    protected ?int $promotionLogId = null;

    #[Groups([
        ContextGroup::AD_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::AD_COMPLETE_INFO,
    ])]
    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): Ad
    {
        $this->price = $price;
        return $this;
    }

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
        ContextGroup::PREMIUM_INFO
    ])]
    public function getId(): string
    {
        return $this->id;
    }

    #[Groups([
        ContextGroup::AD_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::AD_COMPLETE_INFO,
        ContextGroup::PREMIUM_INFO
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

    public function addImage(Image $image): self
    {
        if ($this->images == null) {
            $this->images = new ArrayCollection();
        }
        $this->images->add($image);
        return $this;
    }

    public function removeImage($getById): self
    {
        $this->images->removeElement($getById);

        return $this;
    }

    #[Groups([
        ContextGroup::AD_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::AD_COMPLETE_INFO
    ])]
    public function getImages(): Collection
    {
        return $this->images;
    }

    #[Groups([
        ContextGroup::AD_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::AD_COMPLETE_INFO
    ])]
    public function getPremiumExpiresAt(): ?DateTimeImmutable
    {
        return $this->premiumExpiresAt;
    }

    public function setPremiumExpiresAt(?DateTimeImmutable $premiumExpiresAt): self
    {
        $this->premiumExpiresAt = $premiumExpiresAt;
        return $this;
    }

    #[Groups([
        ContextGroup::AD_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::AD_COMPLETE_INFO
    ])]
    public function getPremiumDuration(): ?int
    {
        return $this->premiumDuration;
    }

    public function setPremiumDuration(?int $premiumDuration): self
    {
        $this->premiumDuration = $premiumDuration;
        return $this;
    }

    public function deactivatePremium(): self
    {
        $this->setPremiumDuration(null);
        $this->setPremiumExpiresAt(null);
        return $this;
    }

    /**
     * @throws DateMalformedStringException
     */
    public function activatePremium(PremiumDuration $duration): self
    {
        $this->setPremiumDuration($duration->value);
        $this->setPremiumExpiresAt($this->now()->modify('+' . $duration->value . ' days'));
        return $this;
    }

    #[Groups([
        ContextGroup::AD_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::AD_COMPLETE_INFO
    ])]
    public function getPremium(): bool
    {
        return $this->premiumDuration !== null && $this->premiumExpiresAt !== null;
    }

    public function getPromotionLogId(): ?int
    {
        return $this->promotionLogId;
    }

    public function setPromotionLogId(?int $promotionLogId): self
    {
        $this->promotionLogId = $promotionLogId;
        return $this;
    }


}