<?php

namespace App\DTOs\Application;
use App\DTOs\BaseDTO;
use App\Enums\ApplicationStatusEnum;
use Carbon\Carbon;

class ApplicationDTO extends BaseDTO
{
    public function __construct(
        public int $id,
        public int $job_id,
        public int $candidate_id,
        public ?string $resume_path = null,
        public ?string $cover_letter = null,
        public ApplicationStatusEnum $status = ApplicationStatusEnum::PENDING,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
    ) {}
}
