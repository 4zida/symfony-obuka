<?php

namespace App\Validator\Phone;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class NationalPhoneNumber extends Constraint
{
    public string $message = '{{ value }} is not a valid national number.';

    public function getTargets(): string|array
    {
        return self::PROPERTY_CONSTRAINT;
    }
}