<?php

namespace App\Services\Candidate;
use App\Models\Candidate;
use App\Models\Job;
use App\Models\Application;
use App\Repositories\Candidate\CandidateRepositoryInterface;
use App\Repositories\Job\JobRepositoryInterface;
use App\Repositories\Application\ApplicationRepositoryInterface;
use App\DTOs\Candidate\CandidateDTO;
use App\DTOs\Candidate\UpdateCandidateDTO;
use App\DTOs\Job\JobDTO;
use App\DTOs\Application\ApplicationDTO;
use App\DTOs\Application\CreateApplicationDTO;
use App\Exceptions\CandidateNotFoundException;
use App\Exceptions\JobNotFoundException;
use App\Exceptions\ApplicationNotFoundException;
use App\Exceptions\DuplicateApplicationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class CandidateService implements CandidateServiceInterface
{
    public function __construct(
        private CandidateRepositoryInterface $candidateRepository,
        private JobRepositoryInterface $jobRepository,
        private ApplicationRepositoryInterface $applicationRepository,
    ) {}

    public function getMyProfile(): CandidateDTO
    {
        $candidate = $this->candidateRepository->findByUserId(Auth::id());

        if (!$candidate) {
            throw new CandidateNotFoundException('Candidate profile not found');
        }

        return $this->mapCandidateToDTO($candidate);
    }

    public function updateMyProfile(UpdateCandidateDTO $dto): CandidateDTO
    {
        $candidate = $this->candidateRepository->findByUserId(Auth::id());

        if (!$candidate) {
            throw new CandidateNotFoundException("Candidate profile not found");
        }

        $data = array_filter($dto->toArray(), fn($value) => $value !== null);
        $this->candidateRepository->update($candidate->id, $data);

        $updatedCandidate = $this->candidateRepository->findByUserId(Auth::id());

        return $this->mapCandidateToDTO($updatedCandidate);
    }

    public function browseJobs(int $perPage = 15): LengthAwarePaginator
    {
        $jobs = $this->jobRepository->all($perPage);
        $jobs->getCollection()->transform(fn(Job $job) => $this->mapJobToDTO($job));

        return $jobs;
    }

    public function getJobDetails(int $jobId): JobDTO
    {
        $job = $this->jobRepository->find($jobId);

        if (!$job) {
            throw new JobNotFoundException("Job with ID $jobId not found");
        }

        return $this->mapJobToDTO($job);
    }

    public function applyToJob(int $jobId, CreateApplicationDTO $dto): ApplicationDTO
    {
        $job = $this->jobRepository->find($jobId);

        if (!$job) {
            throw new JobNotFoundException("Job with ID $jobId not found");
        }

        $candidate = $this->candidateRepository->findByUserId(Auth::id());

        if (!$candidate) {
            throw new CandidateNotFoundException('Candidate profile not found');
        }

        // Check if candidate already applied to this job
        if ($this->applicationRepository->existsForCandidateAndJob($candidate->id, $jobId)) {
            throw new DuplicateApplicationException('You have already applied to this job');
        }

        $applicationData = [
            ...$dto->toArray(),
            'candidate_id' => $candidate->id,
            'job_id' => $jobId,
        ];

        $application = $this->applicationRepository->create($applicationData);

        return $this->mapApplicationToDTO($application);
    }

    public function cancelApplication(int $applicationId): void
    {
        $application = $this->applicationRepository->findById($applicationId);

        if (!$application) {
            throw new ApplicationNotFoundException("Application with ID $applicationId not found");
        }

        $candidate = $this->candidateRepository->findByUserId(Auth::id());

        if (!$candidate || $application->candidate_id !== $candidate->id) {
            throw new ApplicationNotFoundException('You are not authorized to cancel this application');
        }

        $this->applicationRepository->delete($applicationId);
    }

    public function getMyApplications(int $perPage = 15): LengthAwarePaginator
    {
        $candidate = $this->candidateRepository->findByUserId(Auth::id());

        if (!$candidate) {
            throw new CandidateNotFoundException('Candidate profile not found');
        }

        $applications = $this->applicationRepository->findByCandidateId($candidate->id, $perPage);
        $applications->getCollection()->transform(fn(Application $app) => $this->mapApplicationToDTO($app));

        return $applications;
    }

    public function getApplication(int $applicationId): ApplicationDTO
    {
        $application = $this->applicationRepository->findById($applicationId);

        if (!$application) {
            throw new ApplicationNotFoundException("Application with ID $applicationId not found");
        }

        $candidate = $this->candidateRepository->findByUserId(Auth::id());

        if (!$candidate || $application->candidate_id !== $candidate->id) {
            throw new ApplicationNotFoundException('You are not authorized to view this application');
        }

        return $this->mapApplicationToDTO($application);
    }

    private function mapCandidateToDTO(Candidate $candidate): CandidateDTO
    {
        return new CandidateDTO(
            id: $candidate->id,
            user_id: $candidate->user_id,
            resume: $candidate->resume,
            headline: $candidate->headline,
            location: $candidate->location,
            experience_years: $candidate->experience_years,
            phone: $candidate->phone,
            created_at: $candidate->created_at,
            updated_at: $candidate->updated_at,
        );
    }

    private function mapJobToDTO(Job $job): JobDTO
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

    private function mapApplicationToDTO(Application $application): ApplicationDTO
    {
        return new ApplicationDTO(
            id: $application->id,
            candidate_id: $application->candidate_id,
            job_id: $application->job_id,
            cover_letter: $application->cover_letter,
            status: $application->status,
            created_at: $application->created_at,
            updated_at: $application->updated_at,
        );
    }
}
