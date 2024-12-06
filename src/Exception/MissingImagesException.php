<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class MissingImagesException extends UnprocessableEntityHttpException
{
    public const MESSAGE = 'Ad has to have images!';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}