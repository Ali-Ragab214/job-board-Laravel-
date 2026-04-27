<?php

namespace App\Services\Application;

use App\Models\Application;
use App\Repositories\Application\ApplicationRepositoryInterface;
use App\DTOs\Application\ApplicationDTO;
use App\DTOs\Application\CreateApplicationDTO;
use App\DTOs\Application\UpdateApplicationDTO;
use App\Enums\ApplicationStatusEnum;
use App\Exceptions\ApplicationNotFoundException;
use App\Exceptions\DuplicateApplicationException;
use Illuminate\Pagination\LengthAwarePaginator;

class ApplicationService implements ApplicationServiceInterface
{
    public function __construct(
        private ApplicationRepositoryInterface $repository,
    ) {}

    public function getAllApplications(int $perPage = 15): LengthAwarePaginator
    {
        $applications = $this->repository->getAllPaginated($perPage);

        $applications->getCollection()->transform(function ($application) {
            return $this->mapToDTO($application);
        });

        return $applications;
    }

    public function getApplicationById(int $id): ApplicationDTO
    {
        $application = $this->repository->findById($id);

        if (!$application) {
            throw new ApplicationNotFoundException("Application with ID $id not found");
        }

        return $this->mapToDTO($application);
    }

    public function createApplication(CreateApplicationDTO $dto): ApplicationDTO
    {
        $exists = $this->repository->existsForCandidateAndJob(
            $dto->candidate_id,
            $dto->job_id
        );

        if ($exists) {
            throw new DuplicateApplicationException(
                'This candidate has already applied to this job.'
            );
        }

        $data = $dto->toArray();
        $application = $this->repository->create($data);

        return $this->mapToDTO($application);
    }

    public function updateApplication(int $id, UpdateApplicationDTO $dto): ApplicationDTO
    {
        $application = $this->repository->findById($id);

        if (!$application) {
            throw new ApplicationNotFoundException("Application with ID $id not found");
        }

        $data = array_filter($dto->toArray(), fn($value) => $value !== null);

        foreach ($data as $key => $value) {
            $application->$key = $value;
        }

        $application->save();

        return $this->mapToDTO($application);
    }

    public function updateApplicationStatus(int $id, ApplicationStatusEnum $status): ApplicationDTO
    {
        $application = $this->repository->updateStatus($id, $status);

        if (!$application) {
            throw new ApplicationNotFoundException("Application with ID $id not found");
        }

        return $this->mapToDTO($application);
    }

    public function deleteApplication(int $id): void
    {
        $application = $this->repository->findById($id);

        if (!$application) {
            throw new ApplicationNotFoundException("Application with ID $id not found");
        }

        $this->repository->delete($id);
    }

    public function getApplicationsByCandidate(int $candidateId, int $perPage = 15): LengthAwarePaginator
    {
        $applications = $this->repository->findByCandidateId($candidateId, $perPage);

        $applications->getCollection()->transform(function ($application) {
            return $this->mapToDTO($application);
        });

        return $applications;
    }

    public function getApplicationsByJob(int $jobId, int $perPage = 15): LengthAwarePaginator
    {
        $applications = $this->repository->findByJobId($jobId, $perPage);

        $applications->getCollection()->transform(function ($application) {
            return $this->mapToDTO($application);
        });

        return $applications;
    }

    public function checkIfApplicationExists(int $candidateId, int $jobId): bool
    {
        return $this->repository->existsForCandidateAndJob($candidateId, $jobId);
    }

    private function mapToDTO(Application $application): ApplicationDTO
    {
        return new ApplicationDTO(
            id: $application->id,
            job_id: $application->job_id,
            candidate_id: $application->candidate_id,
            resume_path: $application->resume_path,
            cover_letter: $application->cover_letter,
            status: $application->status,
            created_at: $application->created_at,
            updated_at: $application->updated_at,
        );
    }
}
