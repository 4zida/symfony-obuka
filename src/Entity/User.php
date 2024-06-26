<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Util\UserRole;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['list_user', 'list_user_all'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['list_user', 'list_user_all'])]
    #[Assert\NotBlank()]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['list_user', 'list_user_all'])]
    private ?string $role = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[Groups(['list_user_all'])]
    private ?Company $company = null;

    #[ORM\Column(length: 255)]
    #[Groups(['list_user', 'list_user_all'])]
    #[Assert\NotBlank()]
    private ?string $surname = null;


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

    public function setRole(UserRole|string $role): void
    {
        if(is_string($role)){
            $this->role = $role;
        } else {
            $this->role = $role->value;
        }
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
}
