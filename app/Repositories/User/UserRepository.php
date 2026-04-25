<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserAlreadyExistsException;
use App\Exceptions\InvalidUserRoleException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;


class UserRepository implements UserRepositoryInterface
{

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->baseQuery()
            ->latest('created_at')
            ->paginate($perPage);
    }


    public function findById(int $id): ?User
    {
        return $this->baseQuery()->find($id);
    }


    public function findByEmail(string $email): ?User
    {
        return $this->baseQuery()
            ->where('email', $email)
            ->first();
    }


    public function create(array $data): User
    {
        try {
            $filtered = $this->filterAllowedFields($data);

            if (isset($filtered['password'])) {
                $filtered['password'] = Hash::make($filtered['password']);
            }

            return User::query()->create($filtered);
        } catch (QueryException $e) {
            throw new UserAlreadyExistsException('Failed to create user: ' . $e->getMessage());
        }
    }


    public function update(int $id, array $data): ?User
    {
        $user = $this->findById($id);

        if (!$user) {
            throw new UserNotFoundException('User with ID ' . $id . ' not found.');
        }

        try {
            $filtered = $this->filterAllowedFields($data);

            // Hash password if present
            if (isset($filtered['password'])) {
                $filtered['password'] = Hash::make($filtered['password']);
            }

            $user->update($filtered);
        } catch (QueryException $e) {
            throw new UserAlreadyExistsException('Failed to update user: ' . $e->getMessage());
        }

        return $user;
    }


    public function delete(int $id): bool
    {
        return (bool) optional($this->findById($id))->delete();
    }

    public function existsByEmail(string $email): bool
    {
        return User::query()
            ->where('email', $email)
            ->exists();
    }


    public function findByRole(string $role): Collection
    {
        return $this->baseQuery()
            ->where('role', $role)
            ->get();
    }


    private function baseQuery(): Builder
    {
        return User::query()->with(['employer', 'candidate']);
    }


    protected function filterAllowedFields(array $data): array
    {
        $allowed = ['name', 'email', 'password', 'role'];

        return array_intersect_key($data, array_flip($allowed));
    }
}
