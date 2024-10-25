<?php

namespace App\Exception;

use Exception;

class InsufficientCreditsException extends Exception
{
    public function __construct()
    {
        parent::__construct('Not enough credits!');
    }
}