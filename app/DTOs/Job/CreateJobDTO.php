<?php

namespace App\DTOs\Job;

use App\DTOs\BaseDTO;
use App\Enums\JobStatusEnum;
use App\Enums\WorkTypeEnum;
use Carbon\Carbon;

class CreateJobDTO extends BaseDTO
{
    public function __construct(
        public int $employer_id,
        public ?int $category_id,
        public string $title,
        public string $slug,
        public string $description,
        public ?string $responsibilities = null,
        public ?string $qualifications = null,
        public ?float $salary_min = null,
        public ?float $salary_max = null,
        public ?string $location = null,
        public WorkTypeEnum $work_type = WorkTypeEnum::FULL_TIME,
        public string $experience_level = 'entry',
        public JobStatusEnum $status = JobStatusEnum::DRAFT,
        public ?Carbon $application_deadline = null,
    ) {}
}
