<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class Latitude extends Constraint
{
    public const MIN = 41.80000;
    public const MAX = 46.18333;
    public string $message = '{{ value }} is not a valid latitude for Serbia.';

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}