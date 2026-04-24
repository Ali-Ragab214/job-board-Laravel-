<?php

namespace App\Repositories\Application;

use App\Models\Application;
use Illuminate\Pagination\LengthAwarePaginator;

interface ApplicationRepositoryInterface
{
    /**
     * Get all applications with pagination.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find application by ID.
     *
     * @param int $id
     * @return Application|null
     */
    public function findById(int $id): ?Application;

    /**
     * Create a new application.
     *
     * @param array $data
     * @return Application
     */
    public function create(array $data): Application;

    /**
     * Update application status.
     *
     * @param int $id
     * @param string $status
     * @return Application|null
     */
    public function updateStatus(int $id, string $status): ?Application;

    /**
     * Delete application by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Find applications by candidate ID.
     *
     * @param int $candidateId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByCandidateId(int $candidateId);

    /**
     * Find applications by job ID.
     *
     * @param int $jobId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByJobId(int $jobId);

    /**
     * Check if application exists for candidate and job.
     *
     * @param int $candidateId
     * @param int $jobId
     * @return bool
     */
    public function existsForCandidateAndJob(int $candidateId, int $jobId): bool;
}
