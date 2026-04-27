<?php

namespace App\DTOs\Candidate;

use App\DTOs\BaseDTO;

class UpdateCandidateDTO extends BaseDTO
{
    public function __construct(
        public ?string $resume = null,
        public ?string $location = null,
        public ?float $experience_years = null,
        public ?string $phone = null,
        public ?string $headline = null,
    ) {}
}
