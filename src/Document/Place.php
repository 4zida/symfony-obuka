<?php

namespace App\Document;

use App\Repository\PlaceRepository;
use App\Util\ContextGroup;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Serializer\Attribute\Groups;

#[Groups(ContextGroup::PLACE_DETAILS)]
#[MongoDB\Document(collection: 'place', repositoryClass: PlaceRepository::class)]
class Place
{
    #[MongoDB\Id]
    private ?string $id = null;
    #[MongoDB\Field(type: 'string')]
    private ?string $title = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    #[Groups([
        ContextGroup::PRICE_STATS_DETAILS
    ])]
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }
}