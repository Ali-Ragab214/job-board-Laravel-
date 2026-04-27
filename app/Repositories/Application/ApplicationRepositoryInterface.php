<?php

namespace App\Repositories\Application;

use App\Models\Application;
use App\Enums\ApplicationStatusEnum;
use Illuminate\Pagination\LengthAwarePaginator;

interface ApplicationRepositoryInterface
{
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?Application;

    public function create(array $data): Application;

    public function updateStatus(int $id, ApplicationStatusEnum $status): ?Application;

    public function delete(int $id): bool;

    public function findByCandidateId(int $candidateId, int $perPage = 15): LengthAwarePaginator;

    public function findByJobId(int $jobId, int $perPage = 15): LengthAwarePaginator;

    public function existsForCandidateAndJob(int $candidateId, int $jobId): bool;
}
