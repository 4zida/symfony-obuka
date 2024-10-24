<?php

namespace App\Entity;

use App\EventListeners\Entity\UserEntityPrePersistListener;
use App\Repository\UserRepository;
use App\Util\ContextGroup;
use App\Util\UserRole;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(fields: ['email'], message: 'The email address already exists.')]
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
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?Company $company = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private ?string $surname = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    private ?string $password;

    #[ORM\Column(type: 'string')]
    public ?string $passwordNoHash;
    #[ORM\Column(type: "simple_array", nullable: true)]
    private ?array $roles;

    /**
     * @see UserEntityPrePersistListener
     */
    #[ORM\Column(type: 'date_immutable')]
    private ?DateTimeImmutable $createdAt;
    #[ORM\Column(type: 'boolean')]
    private ?bool $isActive;
    #[ORM\Column(type: 'date_immutable')]
    private ?DateTimeImmutable $lastSeenAt;

    /**
     * @var Collection<int, Phone>
     */
    #[ORM\OneToMany(targetEntity: Phone::class, mappedBy: 'user')]
    private Collection $phones;
    #[ORM\Column(type: 'integer')]
    private ?int $creditBalance = null;

    public function __construct()
    {
        $this->phones = new ArrayCollection();
        $this->creditBalance = 0;
    }

    #[Groups([
        ContextGroup::COMPANY_ALL_DETAILS,
        ContextGroup::USER_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::ADMIN_COMPANY_SEARCH,
        ContextGroup::AD_COMPLETE_INFO,
        ContextGroup::PHONE_DETAILS
    ])]
    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups([
        ContextGroup::USER_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::ADMIN_COMPANY_SEARCH,
        ContextGroup::AD_COMPLETE_INFO,
        ContextGroup::USER_WITH_PHONE
    ])]
    public function getPhones(): ?Collection
    {
        return $this->phones;
    }

    public function setPhones(?Collection $phones): self
    {
        $this->phones = $phones;
        return $this;
    }

    public function addPhone(Phone $phone): self
    {
        if (!$this->phones->contains($phone)) {
            $this->phones->add($phone);
            $phone->setUser($this);
        }

        return $this;
    }

    public function removePhone(Phone $phone): self
    {
        if ($this->phones->removeElement($phone)) {
            // set the owning side to null (unless already changed)
            if ($phone->getUser() === $this) {
                $phone->setUser(null);
            }
        }

        return $this;
    }

    public function hasPhones(): ?bool
    {
        return !empty($this->phones);
    }

    #[Groups([
        ContextGroup::USER_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::ADMIN_COMPANY_SEARCH,
    ])]
    public function getLastSeenAt(): ?DateTimeImmutable
    {
        return $this->lastSeenAt;
    }

    public function setLastSeenAt(?DateTimeImmutable $lastSeenAt): self
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

    public function setIsActive(?bool $isActive): self
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
        ContextGroup::AD_COMPLETE_INFO,
        ContextGroup::USER_WITH_PHONE,
        ContextGroup::PHONE_DETAILS
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
        ContextGroup::COMPANY_ALL_DETAILS,
        ContextGroup::USER_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::ADMIN_COMPANY_SEARCH,
        ContextGroup::AD_COMPLETE_INFO,
        ContextGroup::USER_WITH_PHONE,
        ContextGroup::PHONE_DETAILS
    ])]
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->name . " " . $this->surname;
    }

    #[Groups([
        ContextGroup::USER_ALL_DETAILS,
        ContextGroup::SEARCH
    ])]
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
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

    public function setRole(?UserRole $role): self
    {
        $this->role = $role;
        return $this;
    }

    #[Groups([
        ContextGroup::COMPANY_ALL_DETAILS,
        ContextGroup::USER_ALL_DETAILS,
        ContextGroup::SEARCH,
        ContextGroup::ADMIN_COMPANY_SEARCH,
        ContextGroup::AD_COMPLETE_INFO,
        ContextGroup::USER_WITH_PHONE,
        ContextGroup::PHONE_DETAILS
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

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
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
        return (string)$this->email;
    }

    public function setPasswordNoHash(?string $passwordNoHash): self
    {
        $this->passwordNoHash = $passwordNoHash;
        return $this;
    }

    public function isInTheSameCompanyAs(User $user): bool
    {
        return $this->company === $user->getCompany();
    }

    public function getCreditBalance(): ?int
    {
        return $this->creditBalance;
    }

    public function setCreditBalance(?int $creditBalance): self
    {
        $this->creditBalance = $creditBalance;
        return $this;
    }

    public function assertCanSpendCredits(): bool
    {
        return true; // TODO
    }

    /**
     * @throws Exception
     */
    public function deductCredits(int $amount): void
    {
        if ($this->creditBalance < $amount) {
            throw new Exception('Not enough credits');
        }

        $this->creditBalance -= $amount;
    }
}
