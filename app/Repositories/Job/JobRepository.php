<?php

namespace App\Repositories\Job;

use App\Models\Job;

class JobRepository implements JobRepositoryInterface
{
    public function all(int $perPage = 15)
    {
        return Job::query()->paginate($perPage);
    }

    public function find($id): ?Job
    {
        return Job::query()->find($id);
    }

    public function create(array $data): Job
    {
        return Job::query()->create($data);
    }

    public function update($id, array $data): ?Job
    {
        $job = Job::query()->find($id);

        if (!$job) {
            return null;
        }

        $job->update($data);

        return $job; 
    }

    public function delete($id): bool
    {
        return Job::query()->where('id', $id)->delete() > 0;
    }
}
