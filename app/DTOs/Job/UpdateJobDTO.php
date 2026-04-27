<?php

namespace App\DTOs\Job;

use App\DTOs\BaseDTO;
use App\Enums\JobStatusEnum;
use App\Enums\WorkTypeEnum;
use Carbon\Carbon;

class UpdateJobDTO extends BaseDTO
{
    public function __construct(
        public ?int $category_id = null,
        public ?string $title = null,
        public ?string $slug = null,
        public ?string $description = null,
        public ?string $responsibilities = null,
        public ?string $qualifications = null,
        public ?float $salary_min = null,
        public ?float $salary_max = null,
        public ?string $location = null,
        public ?WorkTypeEnum $work_type = null,
        public ?string $experience_level = null,
        public ?JobStatusEnum $status = null,
        public ?Carbon $application_deadline = null,
    ) {}
}
