<?php
namespace App\DTOs\User;
use App\DTOs\BaseDTO;
use App\Enums\UserRoleEnum;

class CreateUserDto extends BaseDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public UserRoleEnum $role,
        public ?string $avatar = null
    ) {}
}
