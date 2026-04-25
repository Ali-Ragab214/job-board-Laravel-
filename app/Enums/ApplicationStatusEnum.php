<?php

namespace App\Enums;

enum ApplicationStatusEnum : string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
}
