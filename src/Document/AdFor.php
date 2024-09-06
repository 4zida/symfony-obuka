<?php

namespace App\Document;

enum AdFor: string
{
    case RENT = 'rent';
    case SALE = 'sale';

    public function isRent(): bool
    {
        return $this === self::RENT;
    }

    public function isSale(): bool
    {
        return $this === self::SALE;
    }
}