<?php

namespace App\Repositories\Application;

use App\Models\Application;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ApplicationRepository implements ApplicationRepositoryInterface
{
    /**
     * Valid application statuses.
     *
     * @var array
     */
    protected const VALID_STATUSES = ['pending', 'accepted', 'rejected'];

    /**
     * Get all applications with pagination.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Application::query()
            ->with(['job', 'candidate'])
            ->latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Find application by ID.
     *
     * @param int $id
     * @return Application|null
     */
    public function findById(int $id): ?Application
    {
        return Application::query()
            ->with(['job', 'candidate'])
            ->find($id);
    }

    /**
     * Create a new application.
     *
     * Prevents duplicate applications (candidate cannot apply to the same job twice).
     *
     * @param array $data
     * @return Application
     * @throws \Exception
     */
    public function create(array $data): Application
    {
        $this->validateApplicationData($data);

        // Check for duplicate application
        if ($this->existsForCandidateAndJob($data['candidate_id'], $data['job_id'])) {
            throw new \Exception('This candidate has already applied to this job.');
        }

        return Application::query()->create($this->filterAllowedFields($data));
    }

    /**
     * Update application status.
     *
     * Only allows updating the status field.
     *
     * @param int $id
     * @param string $status
     * @return Application|null
     * @throws \Exception
     */
    public function updateStatus(int $id, string $status): ?Application
    {
        $this->validateStatus($status);

        $application = $this->findById($id);

        if (!$application) {
            return null;
        }

        $application->update(['status' => $status]);

        return $application;
    }

    /**
     * Delete application by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $application = $this->findById($id);

        if (!$application) {
            return false;
        }

        return $application->delete();
    }

    /**
     * Find applications by candidate ID.
     *
     * @param int $candidateId
     * @return Collection
     */
    public function findByCandidateId(int $candidateId): Collection
    {
        return Application::query()
            ->where('candidate_id', $candidateId)
            ->with(['job', 'candidate'])
            ->latest('created_at')
            ->get();
    }

    /**
     * Find applications by job ID.
     *
     * @param int $jobId
     * @return Collection
     */
    public function findByJobId(int $jobId): Collection
    {
        return Application::query()
            ->where('job_id', $jobId)
            ->with(['job', 'candidate'])
            ->latest('created_at')
            ->get();
    }

    /**
     * Check if application exists for candidate and job.
     *
     * Prevents duplicate applications.
     *
     * @param int $candidateId
     * @param int $jobId
     * @return bool
     */
    public function existsForCandidateAndJob(int $candidateId, int $jobId): bool
    {
        return Application::query()
            ->where('candidate_id', $candidateId)
            ->where('job_id', $jobId)
            ->exists();
    }

    /**
     * Validate application data.
     *
     * @param array $data
     * @return void
     * @throws \Exception
     */
    protected function validateApplicationData(array $data): void
    {
        if (empty($data['candidate_id']) || !is_int($data['candidate_id'])) {
            throw new \Exception('Invalid candidate ID.');
        }

        if (empty($data['job_id']) || !is_int($data['job_id'])) {
            throw new \Exception('Invalid job ID.');
        }

        if (isset($data['status'])) {
            $this->validateStatus($data['status']);
        }
    }

    /**
     * Validate status value.
     *
     * @param string $status
     * @return void
     * @throws \Exception
     */
    protected function validateStatus(string $status): void
    {
        if (!in_array($status, self::VALID_STATUSES, true)) {
            throw new \Exception(
                sprintf(
                    'Invalid status. Allowed statuses: %s',
                    implode(', ', self::VALID_STATUSES)
                )
            );
        }
    }

    /**
     * Filter and return only allowed fields for mass assignment.
     *
     * @param array $data
     * @return array
     */
    protected function filterAllowedFields(array $data): array
    {
        $allowed = ['candidate_id', 'job_id', 'resume_path', 'cover_letter', 'status'];

        return array_intersect_key($data, array_flip($allowed));
    }
}
