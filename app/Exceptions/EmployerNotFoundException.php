<?php
namespace App\Exceptions;
use RuntimeException;
class EmployerNotFoundException extends RuntimeException
{
    public function __construct(string $message = "Employer not found")
    {
        parent::__construct($message);
    }
}
