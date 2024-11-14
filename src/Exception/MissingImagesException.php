<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class MissingImagesException extends UnprocessableEntityHttpException
{
    public function __construct()
    {
        parent::__construct('Ad has to have images!');
    }
}