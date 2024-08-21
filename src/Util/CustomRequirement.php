<?php

namespace App\Util;

interface CustomRequirement
{
    const OBJECT_ID = '^[a-f\d]{24}$';
    const SIGNED_INT = '^-{0,1}[0-9]+$';
}