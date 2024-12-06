<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class InsufficientCreditsException extends Exception
{
    public const MESSAGE = 'Not enough credits!';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, Response::HTTP_PAYMENT_REQUIRED);
    }
}