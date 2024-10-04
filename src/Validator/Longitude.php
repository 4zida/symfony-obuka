<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class Longitude extends Constraint
{
    public const MIN = 18.85000;
    public const MAX = 23.01667;
    public string $message = '{{ value }} is not a valid longitude for Serbia.';

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}