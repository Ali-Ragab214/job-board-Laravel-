<?php

namespace App\DTOs\Employer;

use App\DTOs\BaseDTO;

class UpdateEmployerDTO extends BaseDTO
{
    public function __construct(
        public ?string $company_name = null,
        public ?string $company_logo = null,
        public ?string $company_description = null,
        public ?string $company_website = null,
        public ?string $company_location = null,
        public ?string $phone = null,
    ) {}
}
