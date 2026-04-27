<?php

namespace App\DTOs\Application;

use App\DTOs\BaseDTO;
use App\Enums\ApplicationStatusEnum;

class UpdateApplicationDTO extends BaseDTO
{
    public function __construct(
        public ?string $resume_path = null,
        public ?string $cover_letter = null,
        public ?ApplicationStatusEnum $status = null,
    ) {}
}
