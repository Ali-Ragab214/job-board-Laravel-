<?php

namespace App\Exceptions;

use RuntimeException;

class UnauthorizedUserActionException extends RuntimeException
{
    public function __construct(string $message = 'User is not authorized to perform this action.')
    {
        parent::__construct($message);
    }
}
