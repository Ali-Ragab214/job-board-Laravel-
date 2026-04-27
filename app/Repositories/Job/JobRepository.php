<?php

namespace App\Repositories\Job;

use App\Models\Job;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class JobRepository implements JobRepositoryInterface
{
    protected function baseQuery(): Builder
    {
        return Job::query();
    }

    public function all(int $perPage = 15): LengthAwarePaginator
    {
        return $this->baseQuery()
            ->latest('created_at')
            ->paginate($perPage);
    }

    public function find(int $id): ?Job
    {
        return $this->baseQuery()->find($id);
    }

    public function create(array $data): Job
    {
        return $this->baseQuery()->create($data);
    }

    public function update(int $id, array $data): ?Job
    {
        $job = $this->find($id);

        if (!$job) {
            return null;
        }

        $job->update($data);

        return $job;
    }

    public function delete(int $id): bool
    {
        return (bool) optional($this->find($id))->delete();
    }
}
