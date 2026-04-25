<?php

namespace App\Repositories\Category;

use App\Models\JobCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\QueryException;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return $this->baseQuery()
            ->latest('created_at')
            ->paginate($perPage);
    }

    public function findById(int $id): ?JobCategory
    {
        return $this->baseQuery()->find($id);
    }

    public function findBySlug(string $slug): ?JobCategory
    {
        return $this->baseQuery()
            ->where('slug', $slug)
            ->first();
    }

    public function create(array $data): JobCategory
    {
        try {
            return JobCategory::query()->create($this->filterAllowedFields($data));
        } catch (QueryException $e) {
            throw new \Exception('Failed to create job category: ' . $e->getMessage());
        }
    }

    public function update(int $id, array $data): ?JobCategory
    {
        $category = $this->findById($id);

        if (!$category) {
            return null;
        }

        try {
            $category->update($this->filterAllowedFields($data));
        } catch (QueryException $e) {
            throw new \Exception('Failed to update job category: ' . $e->getMessage());
        }

        return $category;
    }

    public function delete(int $id): bool
    {
        return (bool) optional($this->findById($id))->delete();
    }


    public function existsBySlug(string $slug): bool
    {
        return JobCategory::query()
            ->where('slug', $slug)
            ->exists();
    }

    
    private function baseQuery(): Builder
    {
        return JobCategory::query()->with(['jobs']);
    }

    protected function filterAllowedFields(array $data): array
    {
        $allowed = ['name', 'slug', 'icon'];
        return array_intersect_key($data, array_flip($allowed));
    }
}
