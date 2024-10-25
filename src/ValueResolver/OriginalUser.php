<?php

namespace App\ValueResolver;

use Attribute;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;

#[Attribute(Attribute::TARGET_PARAMETER)]
class OriginalUser extends ValueResolver
{
    public function __construct(string $resolver = OriginalUserValueResolver::class, bool $disabled = false)
    {
        parent::__construct($resolver, $disabled);
    }
}