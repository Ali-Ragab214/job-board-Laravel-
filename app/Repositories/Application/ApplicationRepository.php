<?php

namespace App\Repositories\Application;

use App\Models\Application;
use App\Enums\ApplicationStatusEnum;
use App\Exceptions\DuplicateApplicationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;


class ApplicationRepository implements ApplicationRepositoryInterface
{
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return $this->baseQuery()
            ->latest('created_at')
            ->paginate($perPage);
    }

    public function findById(int $id): ?Application
    {
        return $this->baseQuery()->find($id);
    }

    public function create(array $data): Application
    {
        try {
            return Application::query()->create($this->filterAllowedFields($data));
        } catch (QueryException $e) {
            // Duplicate entry error code (MySQL: 1062)
            if ($e->errorInfo[1] === 1062) {
                throw new DuplicateApplicationException(
                    'This candidate has already applied to this job.'
                );
            }

            throw $e;
        }
    }

    public function updateStatus(int $id, ApplicationStatusEnum $status): ?Application
    {
        $application = $this->findById($id);

        if (!$application) {
            return null;
        }

        $application->update(['status' => $status->value]);

        return $application;
    }

    public function delete(int $id): bool
    {
        return (bool) optional($this->findById($id))->delete();
    }

    public function findByCandidateId(int $candidateId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->baseQuery()
            ->where('candidate_id', $candidateId)
            ->latest('created_at')
            ->paginate($perPage);
    }

    public function findByJobId(int $jobId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->baseQuery()
            ->where('job_id', $jobId)
            ->latest('created_at')
            ->paginate($perPage);
    }

    public function existsForCandidateAndJob(int $candidateId, int $jobId): bool
    {
        return Application::query()
            ->where('candidate_id', $candidateId)
            ->where('job_id', $jobId)
            ->exists();
    }


    private function baseQuery(): Builder
    {
        return Application::query()->with(['job', 'candidate']);
    }

    private function filterAllowedFields(array $data): array
    {
        $allowed = ['candidate_id', 'job_id', 'resume_path', 'cover_letter'];

        $filtered = array_intersect_key($data, array_flip($allowed));

        $filtered['status'] = ApplicationStatusEnum::PENDING->value;

        return $filtered;
    }
}
