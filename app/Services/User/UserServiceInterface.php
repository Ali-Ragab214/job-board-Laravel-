<?php

namespace App\Services\User;

use App\DTOs\User\CreateUserDTO;
use App\DTOs\User\UpdateUserDTO;
use App\DTOs\User\UserDTO;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    public function createUser(CreateUserDTO $dto): UserDTO;

    public function updateUser(int $id, UpdateUserDTO $dto): UserDTO;

    public function getAllUsers(int $perPage = 15): LengthAwarePaginator;

    public function getUserById(int $id): UserDTO;

    public function deleteUser(int $id): void;
}
