<?php

namespace App\Exceptions;

class EmployerNotApprovedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Your employer account has not been approved yet. Please wait for admin approval.');
    }
}
