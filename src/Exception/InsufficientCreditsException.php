<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class InsufficientCreditsException extends Exception
{
    public function __construct()
    {
        parent::__construct('Not enough credits!', Response::HTTP_PAYMENT_REQUIRED);
    }
}