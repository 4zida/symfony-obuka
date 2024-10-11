<?php

namespace App\Validator\Phone;

use Attribute;
use JetBrains\PhpStorm\Deprecated;
use Symfony\Component\Validator\Constraint;

#[Attribute]
#[Deprecated]
class NationalPhoneNumber extends Constraint
{
    public string $message = '{{ value }} is not a valid national number.';

    public function getTargets(): string|array
    {
        return self::PROPERTY_CONSTRAINT;
    }
}