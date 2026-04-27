<?php

namespace App\Services\Job;

use Illuminate\Pagination\LengthAwarePaginator;
use App\DTOs\Job\CreateJobDTO;
use App\DTOs\Job\UpdateJobDTO;
use App\DTOs\Job\JobDTO;

interface JobServiceInterface
{
    public function createJob(CreateJobDTO $dto): JobDTO;

    public function updateJob(int $id, UpdateJobDTO $dto): JobDTO;

    public function getAllJobs(int $perPage = 15): LengthAwarePaginator;

    public function getJobById(int $id): JobDTO;

    public function deleteJob(int $id): void;
}
