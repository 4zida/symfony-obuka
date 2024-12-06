<?php

namespace App\Document;

enum AdType: string
{
    case APARTMENT = 'apartment';
    case HOUSE = 'house';
    case OFFICE = 'office';

    public function getLabel(): string
    {
        return match ($this) {
            self::APARTMENT => 'Stan',
            self::HOUSE => 'Dom',
            self::OFFICE => 'Kancelarija'
        };
    }
}
