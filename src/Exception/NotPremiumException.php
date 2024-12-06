<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class NotPremiumException extends UnprocessableEntityHttpException
{
    public const MESSAGE = 'Ad is not Premium!';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}