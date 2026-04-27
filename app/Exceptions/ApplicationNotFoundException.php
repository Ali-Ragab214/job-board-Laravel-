<?php

namespace App\Exceptions;

class ApplicationNotFoundException extends \Exception
{
    public function __construct(string $message = "Application not found")
    {
        parent::__construct($message);
    }
}
