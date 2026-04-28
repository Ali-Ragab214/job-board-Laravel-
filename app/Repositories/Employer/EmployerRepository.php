<?php

namespace App\Repositories\Employer;

use App\Models\Employer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class EmployerRepository implements EmployerRepositoryInterface
{
    protected function baseQuery(): Builder
    {
        return Employer::query();
    }

    public function all(int $perPage = 15): LengthAwarePaginator
    {
        return $this->baseQuery()
            ->latest('created_at')
            ->paginate($perPage);
    }

    public function find(int $id): ?Employer
    {
        return $this->baseQuery()->find($id);
    }

    public function findByUserId(int $userId): ?Employer
    {
        return $this->baseQuery()->where('user_id', $userId)->first();
    }

    public function create(array $data): Employer
    {
        return $this->baseQuery()->create($data);
    }

    public function update(int $id, array $data): ?Employer
    {
        $employer = $this->find($id);

        if (!$employer) {
            return null;
        }

        $employer->update($data);

        return $employer;
    }

    public function delete(int $id): bool
    {
        return (bool) optional($this->find($id))->delete();
    }
}
