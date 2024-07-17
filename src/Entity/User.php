<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Util\ContextGroup;
use App\Util\UserRole;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[Groups(ContextGroup::USER_DETAILS)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([ContextGroup::USER_COMPANY])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups([ContextGroup::USER_COMPANY, ContextGroup::COMPANY_USERS])]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups([ContextGroup::USER_COMPANY])]
    private ?string $role = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[Groups([ContextGroup::USER_COMPANY])]
    private ?Company $company = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups([ContextGroup::USER_COMPANY, ContextGroup::COMPANY_USERS])]
    #[Assert\NotBlank]
    private ?string $surname = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Groups([ContextGroup::USER_DETAILS, ContextGroup::USER_COMPANY])]
    #[Assert\NotBlank]
    private ?string $email;

    #[ORM\Column(type: 'string')]
    private ?string $password;

    #[ORM\Column(type: 'string')]
    public ?string $passwordNoHash;
    #[ORM\Column(type: "simple_array", nullable: true)]
    private array $roles;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(UserRole|string $role): User
    {
        if(is_string($role)){
            $this->role = $role;
        } else {
            $this->role = $role->value;
        }

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }
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
