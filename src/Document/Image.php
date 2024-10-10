<?php

namespace App\Document;

use App\EventListeners\Document\ImageDocumentPrePersistListener;
use App\Repository\ImageRepository;
use App\Util\ContextGroup;
use App\Util\ImageHelper;
use DateTimeImmutable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[MongoDB\Document(collection: 'image', repositoryClass: ImageRepository::class)]
#[Groups(ContextGroup::IMAGE_DETAILS)]
class Image
{
    public const MAX_RESOLUTION = 8192;
    public const MAX_SIZE = 8000000; // ~8 MB
    public const MIN_RESOLUTION = 256;

    #[MongoDB\Field(type: 'string')]
    #[MongoDB\Id]
    protected ?string $id;
    #[MongoDB\ReferenceOne(targetDocument: Ad::class, orphanRemoval: true)]
    protected ?Ad $ad;
    #[MongoDB\Field(type: 'string')]
    protected ?string $alias;
    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    protected ?string $location;

    /**
     * @see ImageDocumentPrePersistListener
     */
    protected ?DateTimeImmutable $createdAt;
    #[MongoDB\Field(type: 'int')]
    #[Assert\GreaterThanOrEqual(self::MIN_RESOLUTION, message: 'Minimum height of ' . self::MIN_RESOLUTION . ' is required.')]
    #[Assert\LessThanOrEqual(self::MAX_RESOLUTION, message: 'Height cannot exceed ' . self::MAX_RESOLUTION . '.')]
    #[Assert\NotBlank]
    protected ?int $height = null;
    #[MongoDB\Field(type: 'int')]
    #[Assert\GreaterThanOrEqual(self::MIN_RESOLUTION, message: 'Minimum width of ' . self::MIN_RESOLUTION . ' is required.')]
    #[Assert\LessThanOrEqual(self::MAX_RESOLUTION, message: 'Width cannot exceed ' . self::MAX_RESOLUTION . '.')]
    #[Assert\NotBlank]
    protected ?int $width = null;
    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    protected ?string $mimeType = null;
    #[MongoDB\Field(type: 'int')]
    #[Assert\LessThanOrEqual(value: self::MAX_SIZE, message: 'The file is too big, files less than 8 MB required')]
    #[Assert\NotBlank]
    protected ?int $size = null;

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
    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;
        return $this;
    }

    #[Groups([
        ContextGroup::IMAGE_DETAILS,
    ])]
    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): Image
    {
        $this->height = $height;
        return $this;
    }

    #[Groups([
        ContextGroup::IMAGE_DETAILS,
    ])]
    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): Image
    {
        $this->width = $width;
        return $this;
    }

    #[Groups([
        ContextGroup::IMAGE_DETAILS,
    ])]
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): Image
    {
        $this->mimeType = $mimeType;
        return $this;
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

    public function populateFromFile(File $file): self
    {
        [$width, $height] = ImageHelper::getDimensions($file);

        $this
            ->setLocation($file->getRealPath())
            ->setMimeType($file->getMimeType())
            ->setHeight($height)
            ->setWidth($width)
            ->setSize($file->getSize());

        return $this;
    }
}