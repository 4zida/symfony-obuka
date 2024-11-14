<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class NotPremiumException extends UnprocessableEntityHttpException
{
    public function __construct()
    {
        parent::__construct('Ad is not Premium!');
    }
}