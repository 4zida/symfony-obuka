<?php

namespace App\Entity;

use App\Repository\PhoneRepository;
use App\Util\ContextGroup;
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
        ContextGroup::PHONE_DETAILS
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
        ContextGroup::PHONE_DETAILS
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
        ContextGroup::PHONE_DETAILS
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
        ContextGroup::PHONE_DETAILS
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
        ContextGroup::PHONE_DETAILS
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


}
