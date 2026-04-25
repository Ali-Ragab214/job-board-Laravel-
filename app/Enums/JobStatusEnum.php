<?php

namespace App\Enums;

enum JobStatusEnum : string
{
    case OPEN = 'open';
    case CLOSED = 'closed';
    case DRAFT = 'draft';
}

