<?php

namespace App\Document;

use App\Repository\PriceStatsRepository;
use App\Util\ContextGroup;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Serializer\Attribute\Groups;

#[MongoDB\Document(collection: 'price_stats', repositoryClass: PriceStatsRepository::class)]
#[Groups([ContextGroup::PRICE_STATS])]
class PriceStats
{
    #[MongoDB\Field(type: 'string')]
    #[MongoDB\Id]
    private ?string $id = null;
    #[MongoDB\Field(type: 'string')]
    private ?string $placeId = null;
    #[MongoDB\Field(type: 'string')]
    private ?string $type = null;
    #[MongoDB\Field(type: 'float')]
    private ?float $maxPrice = null;
    #[MongoDB\Field(type: 'float')]
    private ?float $minPrice = null;
    #[MongoDB\Field(type: 'float')]
    private ?float $averagePrice = null;
    #[MongoDB\Field(type: 'float')]
    private ?float $pricePerM2 = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function getPlaceId(): ?string
    {
        return $this->placeId;
    }

    public function setPlaceId(?string $placeId): self
    {
        $this->placeId = $placeId;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getMaxPrice(): ?float
    {
        return $this->maxPrice;
    }

    public function setMaxPrice(?float $maxPrice): self
    {
        $this->maxPrice = $maxPrice;
        return $this;
    }

    public function getMinPrice(): ?float
    {
        return $this->minPrice;
    }

    public function setMinPrice(?float $minPrice): self
    {
        $this->minPrice = $minPrice;
        return $this;
    }

    public function getAveragePrice(): ?float
    {
        return $this->averagePrice;
    }

    public function setAveragePrice(?float $averagePrice): self
    {
        $this->averagePrice = $averagePrice;
        return $this;
    }


    public function getPricePerM2(): ?float
    {
        return $this->pricePerM2;
    }

    public function setPricePerM2(?float $pricePerM2): self
    {
        $this->pricePerM2 = $pricePerM2;
        return $this;
    }

    public function create(string $placeId, string $type, float $maxPrice, float $minPrice, float $averagePrice, float $pricePerM2): self
    {
        $this->placeId = $placeId;
        $this->type = $type;
        $this->maxPrice = $maxPrice;
        $this->minPrice = $minPrice;
        $this->averagePrice = $averagePrice;
        $this->pricePerM2 = $pricePerM2;
        return $this;
    }
}