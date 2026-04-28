<?php

namespace App\Services\Candidate;

use App\DTOs\Candidate\CandidateDTO;
use App\DTOs\Candidate\UpdateCandidateDTO;
use App\DTOs\Job\JobDTO;
use App\DTOs\Application\ApplicationDTO;
use App\DTOs\Application\CreateApplicationDTO;
use Illuminate\Pagination\LengthAwarePaginator;

interface CandidateServiceInterface
{
    public function getMyProfile(): CandidateDTO;


    public function updateMyProfile(UpdateCandidateDTO $dto): CandidateDTO;


    public function browseJobs(int $perPage = 15): LengthAwarePaginator;


    public function getJobDetails(int $jobId): JobDTO;


    public function applyToJob(int $jobId, CreateApplicationDTO $dto): ApplicationDTO;


    public function cancelApplication(int $applicationId): void;


    public function getMyApplications(int $perPage = 15): LengthAwarePaginator;

    
    public function getApplication(int $applicationId): ApplicationDTO;
}
