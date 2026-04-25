<?php

namespace App\Exceptions;

use RuntimeException;

class InvalidUserRoleException extends RuntimeException
{
    public function __construct(string $message = 'Invalid user role provided.')
    {
        parent::__construct($message);
    }
}
