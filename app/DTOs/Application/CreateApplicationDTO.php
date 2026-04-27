<?php
namespace App\DTOs\Application;
use App\DTOs\BaseDTO;
use App\Enums\ApplicationStatusEnum;
class CreateApplicationDTO extends BaseDTO
{
    public function __construct(
        public int $job_id,
        public int $candidate_id,
        public ?string $resume_path = null,
        public ?string $cover_letter = null,
        public ApplicationStatusEnum $status = ApplicationStatusEnum::PENDING,
    ) {}

}
