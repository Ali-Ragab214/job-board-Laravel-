<?php

namespace App\Services\User;

use App\Models\Employer;
use App\Models\Candidate;
use App\Repositories\User\UserRepositoryInterface;
use App\DTOs\User\CreateUserDTO;
use App\DTOs\User\UpdateUserDTO;
use App\DTOs\User\UserDTO;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\LogService;
use App\Services\EmailService;
use App\Enums\UserRoleEnum;
use App\Exceptions\UserNotFoundException;
use Illuminate\Support\Facades\Hash;
class UserService implements UserServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $repository,
    ) {}

    public function createUser(CreateUserDTO $dto): UserDTO
    {
        $data = [
            ...$dto->toArray(),
            'password' => Hash::make($dto->password),
        ];

        $user = $this->repository->create($data);

        // Auto-create Employer or Candidate profile based on role
        if ($user->role === UserRoleEnum::EMPLOYER->value) {
            Employer::create(['user_id' => $user->id]);
        } elseif ($user->role === UserRoleEnum::CANDIDATE->value) {
            Candidate::create(['user_id' => $user->id]);
        }

        return $this->mapToDTO($user);
    }

   public function updateUser(int $id, UpdateUserDTO $dto): UserDTO
{
    $user = $this->repository->findById($id);
    if (!$user) {
        throw new UserNotFoundException("User with this ID $id not found");
    }

    $data = $dto->toArray();

    // معالجة الباسورد لو المستخدم طلب تغييره
    if (!empty($data['password'])) {

        if (Hash::check($data['password'], $user->password)) {
            throw new \Exception("New password cannot be the same as old password");
        }

        $data['password'] = Hash::make($data['password']);
    } else {
        unset($data['password']);
    }

    $data = array_filter($data, fn ($value) => $value !== null);

    $updatedUser = $this->repository->update($id, $data);

    return $this->mapToDTO($updatedUser);
}

    public function getAllUsers(int $perPage = 15): LengthAwarePaginator
    {
        $users = $this->repository->paginate($perPage);

        $users->getCollection()->transform(function ($user) {
            return $this->mapToDTO($user);
        });

        return $users;
    }

    public function getUserById(int $id): UserDTO
    {
        $user = $this->repository->findById($id);

        if (!$user) {
            throw new UserNotFoundException("User with this ID $id not found");
        }

        return $this->mapToDTO($user);
    }

    public function deleteUser(int $id): void
    {
        $user = $this->repository->findById($id);

        if (!$user) {
            throw new UserNotFoundException("User with this ID $id not found");
        }

        $this->repository->delete($id);
    }
     private function mapToDTO($user): UserDTO
    {
        return new UserDTO(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            role: UserRoleEnum::from($user->role),
            avatar: $user->avatar,
            created_at: $user->created_at,
            updated_at: $user->updated_at,
        );
    }
}

