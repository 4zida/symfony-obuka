<?php

namespace App\Validator\Phone;

use Attribute;
use JetBrains\PhpStorm\Deprecated;
use Symfony\Component\Validator\Constraint;

#[Attribute]
#[Deprecated]
class E164PhoneNumber extends Constraint
{
    public const E164 = '^\+?[1-9]\d{1,14}$^';
    public string $message = '{{ value }} is not a valid full E164 number.';

    public function getTargets(): string|array
    {
        return self::PROPERTY_CONSTRAINT;
    }
}