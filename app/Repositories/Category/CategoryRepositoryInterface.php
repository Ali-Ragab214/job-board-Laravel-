<?php

namespace App\Repositories\Category;

use App\Models\JobCategory;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryRepositoryInterface
{
    public function getAll(int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?JobCategory;

    public function findBySlug(string $slug): ?JobCategory;

    public function create(array $data): JobCategory;

    public function update(int $id, array $data): ?JobCategory;

    public function delete(int $id): bool;

    public function existsBySlug(string $slug): bool;
}
