<?php

namespace App\Enums;

enum UserRoleEnum : string
{
    case ADMIN = 'admin';
    case EMPLOYER = 'employer';
    case CANDIDATE = 'candidate';
}
