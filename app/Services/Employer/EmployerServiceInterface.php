<?php

namespace App\Services\Employer;

use App\DTOs\Employer\EmployerDTO;
use App\DTOs\Employer\UpdateEmployerDTO;
use App\DTOs\Job\JobDTO;
use App\DTOs\Job\CreateJobDTO;
use App\DTOs\Job\UpdateJobDTO;
use App\DTOs\Application\ApplicationDTO;
use App\Enums\ApplicationStatusEnum;
use Illuminate\Pagination\LengthAwarePaginator;

interface EmployerServiceInterface
{
    public function getMyProfile(): EmployerDTO;


    public function updateMyProfile(UpdateEmployerDTO $dto): EmployerDTO;

    public function createJob(CreateJobDTO $dto): JobDTO;


    public function updateJob(int $jobId, UpdateJobDTO $dto): JobDTO;


    public function deleteJob(int $jobId): void;


    public function getMyJobs(int $perPage = 15): LengthAwarePaginator;

    public function getMyJob(int $jobId): JobDTO;

    public function getJobApplications(int $jobId, int $perPage = 15): LengthAwarePaginator;


    public function acceptApplication(int $applicationId): ApplicationDTO;


    public function rejectApplication(int $applicationId): ApplicationDTO;
}
