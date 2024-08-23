<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Util\ContextGroup;
use App\Util\UserRole;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[Groups(ContextGroup::ADMIN_USER_SEARCH)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(type: 'string', enumType: UserRole::class)]
    private ?UserRole $role = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Company $company = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private ?string $surname = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank]
    private ?string $email;

    #[ORM\Column(type: 'string')]
    private ?string $password;

    #[ORM\Column(type: 'string')]
    public ?string $passwordNoHash;
    #[ORM\Column(type: "simple_array", nullable: true)]
    private ?array $roles;
    #[ORM\Column(type: 'date_immutable')]
    private ?DateTimeImmutable $createdAt;
    #[ORM\Column(type: 'boolean')]
    private ?bool $isActive;
    #[ORM\Column(type: 'date_immutable')]
    private ?DateTimeImmutable $lastSeenAt;

    #[Groups([
        ContextGroup::USER_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::ADMIN_COMPANY_SEARCH,
    ])]
    public function getLastSeenAt(): ?DateTimeImmutable
    {
        return $this->lastSeenAt;
    }

    public function setLastSeenAt(?DateTimeImmutable $lastSeenAt): User
    {
        $this->lastSeenAt = $lastSeenAt;
        return $this;
    }

    #[Groups([
        ContextGroup::USER_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::ADMIN_COMPANY_SEARCH,
    ])]
    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): User
    {
        $this->isActive = $isActive;
        return $this;
    }

    #[Groups([
        ContextGroup::USER_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::ADMIN_COMPANY_SEARCH,
    ])]
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    #[Groups([
        ContextGroup::COMPANY_ALL_DETAILS,
        ContextGroup::USER_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::ADMIN_COMPANY_SEARCH,
    ])]
    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups([
        ContextGroup::COMPANY_ALL_DETAILS,
        ContextGroup::USER_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::ADMIN_COMPANY_SEARCH,
    ])]
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    #[Groups([
        ContextGroup::USER_ALL_DETAILS,
        ContextGroup::SEARCH
    ])]
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }

    #[Groups([
        ContextGroup::COMPANY_ALL_DETAILS,
        ContextGroup::USER_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::ADMIN_COMPANY_SEARCH,
    ])]
    public function getRole(): ?UserRole
    {
        return $this->role;
    }

    public function setRole(?UserRole $role): User
    {
        $this->role = $role;
        return $this;
    }

    #[Groups([
        ContextGroup::COMPANY_ALL_DETAILS,
        ContextGroup::USER_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::ADMIN_COMPANY_SEARCH,
    ])]
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    #[Groups([
        ContextGroup::COMPANY_ALL_DETAILS,
        ContextGroup::USER_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::ADMIN_COMPANY_SEARCH,
    ])]
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
    }

    /**
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function setRoles(array $roles): User
    {
        $this->roles = $roles;
        return $this;
    }

    public function setPasswordNoHash(?string $passwordNoHash): User
    {
        $this->passwordNoHash = $passwordNoHash;
        return $this;
    }
}
