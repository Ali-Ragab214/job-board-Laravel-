<?php
namespace App\DTOs\User;
use App\DTOs\BaseDTO;
use App\Enums\UserRoleEnum;
class UpdateUserDTO extends BaseDTO
{
    public function __construct(
        public ?string $name = null,
        public ?string $email = null,
        public ?string $password = null,
        public ?UserRoleEnum $role = null,
        public ?string $avatar = null
    ) {}
}
