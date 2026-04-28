<?php

namespace App\Exceptions;

class CandidateNotFoundException extends \Exception
{
    public function __construct(string $message = "Candidate not found")
    {
        parent::__construct($message);
    }
}
