<?php

namespace App\Repositories\Employer;

use App\Models\Employer;
use Illuminate\Pagination\LengthAwarePaginator;

interface EmployerRepositoryInterface
{
    public function all(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Employer;

    public function findByUserId(int $userId): ?Employer;

    public function create(array $data): Employer;

    public function update(int $id, array $data): ?Employer;

    public function delete(int $id): bool;
}
