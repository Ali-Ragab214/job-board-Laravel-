<?php

namespace App\DTOs\User;
use App\DTOs\BaseDTO;
use App\Enums\UserRoleEnum;
use Carbon\Carbon;

class UserDTO extends BaseDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public UserRoleEnum $role,
        public ?string $avatar = null,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
    ) {}
}

