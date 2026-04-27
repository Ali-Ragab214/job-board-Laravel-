<?php

namespace App\Services\Application;

use App\DTOs\Application\ApplicationDTO;
use App\DTOs\Application\CreateApplicationDTO;
use App\DTOs\Application\UpdateApplicationDTO;
use App\Enums\ApplicationStatusEnum;
use Illuminate\Pagination\LengthAwarePaginator;

interface ApplicationServiceInterface
{
    public function getAllApplications(int $perPage = 15): LengthAwarePaginator;

    public function getApplicationById(int $id): ApplicationDTO;

    public function createApplication(CreateApplicationDTO $dto): ApplicationDTO;

    public function updateApplication(int $id, UpdateApplicationDTO $dto): ApplicationDTO;

    public function updateApplicationStatus(int $id, ApplicationStatusEnum $status): ApplicationDTO;

    public function deleteApplication(int $id): void;

    public function getApplicationsByCandidate(int $candidateId, int $perPage = 15): LengthAwarePaginator;

    public function getApplicationsByJob(int $jobId, int $perPage = 15): LengthAwarePaginator;

    public function checkIfApplicationExists(int $candidateId, int $jobId): bool;
}
