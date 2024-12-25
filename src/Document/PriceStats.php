<?php

namespace App\Document;

use App\Repository\PriceStatsRepository;
use App\Util\ContextGroup;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Serializer\Attribute\Groups;

#[MongoDB\Document(collection: 'price_stats', repositoryClass: PriceStatsRepository::class)]
#[Groups([ContextGroup::ADMIN_PRICE_STATS])]
class PriceStats
{
    #[MongoDB\Field(type: 'string')]
    #[MongoDB\Id]
    private ?string $id = null;
    #[MongoDB\ReferenceOne(storeAs: 'id', targetDocument: Place::class)]
    private ?Place $place = null;
    #[MongoDB\Field(type: 'string')]
    private ?string $type = null;
    #[MongoDB\Field(type: 'float')]
    private ?float $maxPrice = null;
    #[MongoDB\Field(type: 'float')]
    private ?float $minPrice = null;
    #[MongoDB\Field(type: 'float')]
    private ?float $averagePrice = null;

    public function getId(): string
    {
        return $this->id;
    }

    #[Groups([
        ContextGroup::PRICE_STATS_DETAILS
    ])]
    public function getPlaceName(): ?string
    {
        return $this->place->getTitle();
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;
        return $this;
    }

    #[Groups([
        ContextGroup::PRICE_STATS_DETAILS
    ])]
    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }

    #[Groups([
        ContextGroup::PRICE_STATS_DETAILS
    ])]
    public function getMaxPrice(): ?float
    {
        return $this->maxPrice;
    }

    public function setMaxPrice(?float $maxPrice): self
    {
        $this->maxPrice = $maxPrice;
        return $this;
    }

    #[Groups([
        ContextGroup::PRICE_STATS_DETAILS
    ])]
    public function getMinPrice(): ?float
    {
        return $this->minPrice;
    }

    public function setMinPrice(?float $minPrice): self
    {
        $this->minPrice = $minPrice;
        return $this;
    }

    #[Groups([
        ContextGroup::PRICE_STATS_DETAILS
    ])]
    public function getAveragePrice(): ?float
    {
        return $this->averagePrice;
    }

    public function setAveragePrice(?float $averagePrice): self
    {
        $this->averagePrice = $averagePrice;
        return $this;
    }

    public static function create(Place $place, string $type, float $maxPrice, float $minPrice, float $averagePrice): self
    {
        return (new PriceStats())
            ->setPlace($place)
            ->setType($type)
            ->setMaxPrice($maxPrice)
            ->setMinPrice($minPrice)
            ->setAveragePrice($averagePrice);
    }
}