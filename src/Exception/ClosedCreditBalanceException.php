<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ClosedCreditBalanceException extends Exception
{
    public function __construct()
    {
        parent::__construct('You have no permission to spend credits!', Response::HTTP_FORBIDDEN);
    }
}