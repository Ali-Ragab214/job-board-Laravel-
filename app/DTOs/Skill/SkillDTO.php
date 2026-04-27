<?php

namespace App\DTOs\Skill;

use App\DTOs\BaseDTO;
use Carbon\Carbon;

class SkillDTO extends BaseDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
    ) {}
}
