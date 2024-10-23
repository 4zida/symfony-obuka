<?php

namespace App\Entity;

use App\EventListeners\Entity\CompanyEntityPrePersistListener;
use App\Repository\CompanyRepository;
use App\Util\ContextGroup;
use App\Validator\Latitude;
use App\Validator\Longitude;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(fields: ['name'])]
#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[Groups(ContextGroup::ADMIN_COMPANY_SEARCH)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $address = null;
    #[ORM\Column(type: 'text')]
    private ?string $aboutUs = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'company')]
    private Collection $users;

    /**
     * @see CompanyEntityPrePersistListener
     */
    #[ORM\Column(type: 'date_immutable')]
    private ?DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'boolean')]
    private ?bool $isActive;
    #[Latitude]
    #[ORM\Column(type: 'float')]
    private ?float $latitude;
    #[Longitude]
    #[ORM\Column(type: 'float')]
    private ?float $longitude;

    #[Groups([
        ContextGroup::COMPANY_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::ADMIN_USER_SEARCH
    ])]
    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }

    #[Groups([
        ContextGroup::COMPANY_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::ADMIN_USER_SEARCH
    ])]
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    #[Groups([
        ContextGroup::USER_ALL_DETAILS,
        ContextGroup::COMPANY_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::ADMIN_USER_SEARCH
    ])]
    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups([
        ContextGroup::USER_ALL_DETAILS,
        ContextGroup::COMPANY_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::ADMIN_USER_SEARCH
    ])]
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    #[Groups([
        ContextGroup::USER_ALL_DETAILS,
        ContextGroup::COMPANY_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::ADMIN_USER_SEARCH
    ])]
    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    #[Groups([
        ContextGroup::COMPANY_ALL_DETAILS,
        ContextGroup::SEARCH
    ])]
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setCompany($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCompany() === $this) {
                $user->setCompany(null);
            }
        }

        return $this;
    }

    #[Groups([
        ContextGroup::USER_ALL_DETAILS,
        ContextGroup::COMPANY_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::ADMIN_USER_SEARCH
    ])]
    public function getAboutUs(): ?string
    {
        return $this->aboutUs;
    }

    public function setAboutUs(?string $aboutUs): self
    {
        $this->aboutUs = $aboutUs;
        return $this;
    }

    #[Groups([
        ContextGroup::USER_ALL_DETAILS,
        ContextGroup::COMPANY_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::ADMIN_USER_SEARCH
    ])]
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    #[Groups([
        ContextGroup::USER_ALL_DETAILS,
        ContextGroup::COMPANY_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::ADMIN_USER_SEARCH
    ])]
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }
}
