<?php

namespace App\DTOs\Employer;

use App\DTOs\BaseDTO;

class CreateEmployerDTO extends BaseDTO
{
    public function __construct(
        public int $user_id,
        public string $company_name,
        public ?string $company_logo = null,
        public ?string $company_description = null,
        public ?string $company_website = null,
        public ?string $company_location = null,
        public ?string $phone = null,
    ) {}
}
