<?php

namespace App\Entity;

use App\Repository\PhoneRepository;
use Doctrine\ORM\Mapping as ORM;
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
    #[ORM\Column(name: 'phone', type: 'string', length: 64)]
    private ?string $full = null;
    #[ORM\Column(name: 'national', type: 'string', length: 64)]
    private ?string $national = null;
    #[ORM\Column(name: 'international', type: 'string', length: 64)]
    private ?string $international = null;
    #[ORM\Column(name: 'isViber', type: 'boolean')]
    private ?bool $isViber = null;
    #[ORM\Column(name: 'countryCode', type: 'string', length: 64)]
    private ?string $countryCode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromPhoneNumber(PhoneNumber $phoneNumber): Phone
    {
        $phone = new self();
        $phone->setFull(self::formatToFull($phoneNumber));
        $countryCode = PhoneNumberUtil::getInstance()->getRegionCodeForNumber($phoneNumber);
        $phone->setCountryCode($countryCode);
        $national = $countryCode === self::REGION_CODE ?
            self::formatToNational($phoneNumber) :
            self::formatToInternational($phoneNumber);
        $phone->setNational($national);

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

    public function getFull(): ?string
    {
        return $this->full;
    }

    public function setFull(?string $full): Phone
    {
        $this->full = $full;
        return $this;
    }

    public function getNational(): ?string
    {
        return $this->national;
    }

    public function setNational(?string $national): Phone
    {
        $this->national = $national;
        return $this;
    }

    public function getInternational(): ?string
    {
        return $this->international;
    }

    public function setInternational(?string $international): Phone
    {
        $this->international = $international;
        return $this;
    }

    public function getIsViber(): ?bool
    {
        return $this->isViber;
    }

    public function setIsViber(?bool $isViber): Phone
    {
        $this->isViber = $isViber;
        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }


}
