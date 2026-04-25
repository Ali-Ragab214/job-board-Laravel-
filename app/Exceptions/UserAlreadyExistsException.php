<?php

namespace App\Exceptions;

use RuntimeException;

class UserAlreadyExistsException extends RuntimeException
{
    public function __construct(string $message = 'User with this email already exists.')
    {
        parent::__construct($message);
    }
}
