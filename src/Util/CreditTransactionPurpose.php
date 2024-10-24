<?php

namespace App\Util;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

enum CreditTransactionPurpose: string
{
    case PREMIUM = 'premium';

    public function getPrice(): int
    {
        return match ($this) {
            self::PREMIUM => 10,
            default => throw new UnprocessableEntityHttpException('Price not defined for type: ' . $this->value)
        };
    }
}