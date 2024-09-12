<?php

namespace App\Entity;

use App\Repository\PhoneRepository;
use App\Util\ContextGroup;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

#[ORM\Entity(repositoryClass: PhoneRepository::class)]
class Phone
{
    public const REGION_CODE = 'RS';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(name: 'full', type: 'string', length: 64)]
    private ?string $full = null;
    #[ORM\Column(name: 'national', type: 'string', length: 64, nullable: true)]
    private ?string $national = null;
    #[ORM\Column(name: 'international', type: 'string', length: 64, nullable: true)]
    private ?string $international = null;
    #[ORM\Column(name: 'isViber', type: 'boolean', nullable: true)]
    private ?bool $isViber = null;
    #[ORM\Column(name: 'countryCode', type: 'string', length: 64)]
    private ?string $countryCode = null;
    #[ORM\ManyToOne(inversedBy: 'phones')]
    private ?User $user = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setFromPhoneNumber(PhoneNumber $phoneNumber): Phone
    {
        $phone = new self();
        $countryCode = PhoneNumberUtil::getInstance()->getRegionCodeForNumber($phoneNumber);
        $national = $countryCode === self::REGION_CODE ?
            self::formatToNational($phoneNumber) :
            self::formatToInternational($phoneNumber);

        $phone->setNational($national)
            ->setCountryCode($countryCode)
            ->setFull(self::formatToFull($phoneNumber));

        return $phone;
    }

    public function formatToFull(PhoneNumber $phoneNumber): string
    {
        return PhoneNumberUtil::getInstance()->format($phoneNumber, PhoneNumberFormat::E164);
    }
    public function formatToNational(PhoneNumber $phoneNumber): string
    {
        return PhoneNumberUtil::getInstance()->format($phoneNumber, PhoneNumberFormat::NATIONAL);
    }
    public function formatToInternational(PhoneNumber $phoneNumber): string
    {
        return PhoneNumberUtil::getInstance()->format($phoneNumber, PhoneNumberFormat::INTERNATIONAL);
    }

    #[Groups([
        ContextGroup::PHONE_DETAILS,
        ContextGroup::USER_WITH_PHONE
    ])]
    public function getFull(): ?string
    {
        return $this->full;
    }

    public function setFull(?string $full): Phone
    {
        $this->full = $full;
        return $this;
    }

    #[Groups([
        ContextGroup::PHONE_DETAILS,
        ContextGroup::USER_WITH_PHONE
    ])]
    public function getNational(): ?string
    {
        return $this->national;
    }

    public function setNational(?string $national): Phone
    {
        $this->national = $national;
        return $this;
    }

    #[Groups([
        ContextGroup::PHONE_DETAILS,
        ContextGroup::USER_WITH_PHONE
    ])]
    public function getInternational(): ?string
    {
        return $this->international;
    }

    public function setInternational(?string $international): Phone
    {
        $this->international = $international;
        return $this;
    }

    #[Groups([
        ContextGroup::PHONE_DETAILS,
        ContextGroup::USER_WITH_PHONE
    ])]
    public function getIsViber(): ?bool
    {
        return $this->isViber;
    }

    public function setIsViber(?bool $isViber): Phone
    {
        $this->isViber = $isViber;
        return $this;
    }

    #[Groups([
        ContextGroup::PHONE_DETAILS,
        ContextGroup::USER_WITH_PHONE
    ])]
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): Phone
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): Phone
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setPhones($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getPhones() === $this) {
                $user->setPhones(null);
            }
        }

        return $this;
    }


}
