<?php

namespace App\DTOs\Candidate;

use App\DTOs\BaseDTO;
use Carbon\Carbon;

class CandidateDTO extends BaseDTO
{
    public function __construct(
        public int $id,
        public int $user_id,
        public ?string $resume = null,
        public ?string $location = null,
        public ?float $experience_years = null,
        public ?string $phone = null,
        public ?string $headline = null,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
    ) {}
}
