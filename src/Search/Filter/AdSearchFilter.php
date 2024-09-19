<?php

namespace App\Search\Filter;

use App\Document\AdFor;
use Nebkam\OdmSearchParam\SearchParam;
use Nebkam\OdmSearchParam\SearchParamDirection;
use Nebkam\OdmSearchParam\SearchParamType;
use Symfony\Component\Validator\Constraints as Assert;

class AdSearchFilter
{
    #[Assert\Type('integer')]
    #[SearchParam(type: SearchParamType::RangeInt, direction: SearchParamDirection::From, field: 'floor')]
    protected ?int $floorFrom = null;
    #[Assert\Type('integer')]
    #[SearchParam(type: SearchParamType::RangeInt, direction: SearchParamDirection::To, field: 'floor')]
    protected ?int $floorTo = null;
    #[Assert\Type('integer')]
    #[Assert\GreaterThanOrEqual(value: 0)]
    #[SearchParam(type: SearchParamType::RangeInt, direction: SearchParamDirection::From, field: 'm2')]
    protected ?int $m2From = null;
    #[Assert\Type('integer')]
    #[Assert\GreaterThanOrEqual(value: 0)]
    #[SearchParam(type: SearchParamType::RangeInt, direction: SearchParamDirection::To, field: 'm2')]
    protected ?int $m2To = null;
    #[Assert\Type('integer')]
    #[Assert\GreaterThanOrEqual(value: 0)]
    #[SearchParam(type: SearchParamType::RangeInt, direction: SearchParamDirection::From, field: 'price')]
    protected ?int $priceFrom = null;
    #[Assert\Type('integer')]
    #[Assert\GreaterThanOrEqual(value: 0)]
    #[SearchParam(type: SearchParamType::RangeInt, direction: SearchParamDirection::To, field: 'price')]
    protected ?int $priceTo = null;
    #[Assert\Type('string')]
    #[SearchParam(type: SearchParamType::String, field: 'address')]
    protected ?string $address = null;
    #[SearchParam(type: SearchParamType::StringEnum, field: 'for')]
    protected ?AdFor $for = null;

    public function getPriceTo(): ?int
    {
        return $this->priceTo;
    }

    public function setPriceTo(?int $priceTo): AdSearchFilter
    {
        $this->priceTo = $priceTo;
        return $this;
    }

    public function getPriceFrom(): ?int
    {
        return $this->priceFrom;
    }

    public function setPriceFrom(?int $priceFrom): AdSearchFilter
    {
        $this->priceFrom = $priceFrom;
        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): AdSearchFilter
    {
        $this->address = $address;
        return $this;
    }

    public function getFloorFrom(): ?int
    {
        return $this->floorFrom;
    }

    public function setFloorFrom(?int $floorFrom): AdSearchFilter
    {
        $this->floorFrom = $floorFrom;
        return $this;
    }

    public function getFloorTo(): ?int
    {
        return $this->floorTo;
    }

    public function setFloorTo(?int $floorTo): AdSearchFilter
    {
        $this->floorTo = $floorTo;
        return $this;
    }

    public function getM2From(): ?int
    {
        return $this->m2From;
    }

    public function setM2From(?int $m2From): AdSearchFilter
    {
        $this->m2From = $m2From;
        return $this;
    }

    public function getM2To(): ?int
    {
        return $this->m2To;
    }

    public function setM2To(?int $m2To): AdSearchFilter
    {
        $this->m2To = $m2To;
        return $this;
    }

    public function getFor(): ?AdFor
    {
        return $this->for;
    }

    public function setFor(?AdFor $for): AdSearchFilter
    {
        $this->for = $for;
        return $this;
    }
}