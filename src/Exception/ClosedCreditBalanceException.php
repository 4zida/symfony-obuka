<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ClosedCreditBalanceException extends Exception
{
    public const MESSAGE = 'You have no permission to spend credits!';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, Response::HTTP_FORBIDDEN);
    }
}