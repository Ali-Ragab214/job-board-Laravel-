<?php

namespace App\Services\Job;

use App\Models\Job;
use App\Repositories\Job\JobRepositoryInterface;
use App\DTOs\Job\CreateJobDTO;
use App\DTOs\Job\UpdateJobDTO;
use App\DTOs\Job\JobDTO;
use App\Exceptions\JobNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

class JobService implements JobServiceInterface
{
    public function __construct(
        private JobRepositoryInterface $repository,
    ) {}

    public function getAllJobs(int $perPage = 15): LengthAwarePaginator
    {
        $jobs =  $this->repository->all($perPage);
        $jobs->getCollection()->transform(fn(Job $job) => $this->mapToDTO($job));
        return $jobs;
    }

    public function getJobById(int $id): JobDTO
    {
        $job = $this->repository->find($id);

        if (!$job) {
            throw new JobNotFoundException("Job with ID $id not found");
        }

        return $this->mapToDTO($job);
    }

    public function createJob(CreateJobDTO $dto): JobDTO
    {
        $data = $dto->toArray();
        $job = $this->repository->create($data);

        return $this->mapToDTO($job);
    }

    public function updateJob(int $id, UpdateJobDTO $dto): JobDTO
    {
        $job = $this->repository->find($id);

        if (!$job) {
            throw new JobNotFoundException("Job with ID $id not found");
        }

        $data = array_filter($dto->toArray(), fn($value) => $value !== null);
        $this->repository->update($id, $data);

        $updatedJob = $this->repository->find($id);

        return $this->mapToDTO($updatedJob);
    }

    public function deleteJob(int $id): void
    {
        $job = $this->repository->find($id);

        if (!$job) {
            throw new JobNotFoundException("Job with ID $id not found");
        }

        $this->repository->delete($id);
    }

    private function mapToDTO(Job $job): JobDTO
    {
        return new JobDTO(
            id: $job->id,
            employer_id: $job->employer_id,
            category_id: $job->category_id,
            title: $job->title,
            slug: $job->slug,
            description: $job->description,
            responsibilities: $job->responsibilities,
            qualifications: $job->qualifications,
            salary_min: $job->salary_min,
            salary_max: $job->salary_max,
            location: $job->location,
            work_type: $job->work_type,
            experience_level: $job->experience_level,
            status: $job->status,
            application_deadline: $job->application_deadline,
            views_count: $job->views_count,
            created_at: $job->created_at,
            updated_at: $job->updated_at,
        );
    }
}
