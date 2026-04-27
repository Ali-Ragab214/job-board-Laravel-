<?php

namespace App\Exceptions;

class JobNotFoundException extends \Exception
{
    public function __construct(string $message = "Job not found")
    {
        parent::__construct($message);
    }
}
