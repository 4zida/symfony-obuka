<?php

namespace App\Search\Filter;

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


}