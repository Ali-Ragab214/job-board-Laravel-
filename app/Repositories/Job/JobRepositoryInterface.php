<?php

namespace App\Repositories\Job;

use App\Models\Job;
use Illuminate\Pagination\LengthAwarePaginator;

interface JobRepositoryInterface
{
    public function all(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Job;

    public function create(array $data): Job;

    public function update(int $id, array $data): ?Job;

    public function delete(int $id): bool;
}
