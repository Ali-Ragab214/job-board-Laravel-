<?php

namespace App\Repositories\Candidate;

use App\Models\Candidate;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class CandidateRepository implements CandidateRepositoryInterface
{
    protected function baseQuery(): Builder
    {
        return Candidate::query();
    }

    public function all(int $perPage = 15): LengthAwarePaginator
    {
        return $this->baseQuery()
            ->latest('created_at')
            ->paginate($perPage);
    }

    public function find(int $id): ?Candidate
    {
        return $this->baseQuery()->find($id);
    }

    public function findByUserId(int $userId): ?Candidate
    {
        return $this->baseQuery()->where('user_id', $userId)->first();
    }

    public function create(array $data): Candidate
    {
        return $this->baseQuery()->create($data);
    }

    public function update(int $id, array $data): ?Candidate
    {
        $candidate = $this->find($id);

        if (!$candidate) {
            return null;
        }

        $candidate->update($data);

        return $candidate;
    }

    public function delete(int $id): bool
    {
        return (bool) optional($this->find($id))->delete();
    }
}
