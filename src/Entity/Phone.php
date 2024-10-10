<?php

namespace App\Entity;

use App\Repository\PhoneRepository;
use App\Util\ContextGroup;
use App\Validator\Phone\E164PhoneNumber;
use App\Validator\Phone\InternationalPhoneNumber;
use App\Validator\Phone\NationalPhoneNumber;
use Doctrine\ORM\Mapping as ORM;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: PhoneRepository::class)]
#[Groups(ContextGroup::PHONE_DETAILS)]
class Phone
{
    public const REGION_CODE = 'RS';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(name: 'full', type: 'string', length: 64)]
    #[E164PhoneNumber]
    private ?string $full = null;
    #[ORM\Column(name: 'national', type: 'string', length: 64, nullable: true)]
    #[NationalPhoneNumber]
    private ?string $national = null;
    #[ORM\Column(name: 'international', type: 'string', length: 64, nullable: true)]
    #[InternationalPhoneNumber]
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

    #[Groups([
        ContextGroup::PHONE_DETAILS,
        ContextGroup::USER_WITH_PHONE,
        ContextGroup::AD_COMPLETE_INFO,
        ContextGroup::ADMIN_USER_SEARCH,
    ])]
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setFromPhoneNumber(PhoneNumber $phoneNumber): Phone
    {
        $phone = new self();
        $countryCode = PhoneNumberUtil::getInstance()->getRegionCodeForNumber($phoneNumber);

        if ($countryCode === self::REGION_CODE) {
            $national = self::formatToNational($phoneNumber);
        } else {
            $national = self::formatToInternational($phoneNumber);
        }

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
        ContextGroup::USER_WITH_PHONE,
        ContextGroup::AD_COMPLETE_INFO,
        ContextGroup::ADMIN_USER_SEARCH,
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
        ContextGroup::USER_WITH_PHONE,
        ContextGroup::AD_COMPLETE_INFO,
        ContextGroup::ADMIN_USER_SEARCH,
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
        ContextGroup::USER_WITH_PHONE,
        ContextGroup::AD_COMPLETE_INFO,
        ContextGroup::ADMIN_USER_SEARCH,
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
        ContextGroup::USER_WITH_PHONE,
        ContextGroup::AD_COMPLETE_INFO,
        ContextGroup::ADMIN_USER_SEARCH,
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
        ContextGroup::USER_WITH_PHONE,
        ContextGroup::AD_COMPLETE_INFO,
        ContextGroup::ADMIN_USER_SEARCH,
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
}
