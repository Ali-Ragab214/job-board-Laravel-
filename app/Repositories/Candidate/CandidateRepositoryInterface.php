<?php

namespace App\Repositories\Candidate;

use App\Models\Candidate;
use Illuminate\Pagination\LengthAwarePaginator;

interface CandidateRepositoryInterface
{
    public function all(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Candidate;

    public function findByUserId(int $userId): ?Candidate;

    public function create(array $data): Candidate;

    public function update(int $id, array $data): ?Candidate;

    public function delete(int $id): bool;
}
