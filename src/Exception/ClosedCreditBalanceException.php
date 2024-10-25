<?php

namespace App\Exception;

use Exception;

class ClosedCreditBalanceException extends Exception
{
    public function __construct()
    {
        parent::__construct('You have no permission to spend credits!');
    }
}