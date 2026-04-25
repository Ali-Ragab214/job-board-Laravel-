<?php

namespace App\Exceptions;

use RuntimeException;

class DuplicateApplicationException extends RuntimeException
{
    public function __construct(string $message = 'This candidate has already applied to this job.')
    {
        parent::__construct($message);
    }
}
