<?php

namespace App\Exceptions;

class SkillNotFoundException extends \Exception
{
    public function __construct(string $message = "Skill not found")
    {
        parent::__construct($message);
    }
}
