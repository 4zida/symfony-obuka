<?php

namespace App\Exception;

use Exception;

class EmptyRepositoryException extends Exception
{
    public $message = 'Repository returned an empty array';

    public function __construct()
    {
        parent::__construct($this->message);
    }
}