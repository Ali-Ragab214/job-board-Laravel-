<?php
namespace App\Services\Employer;
use App\Models\Employer;
use App\Models\Job;
use App\Models\Application;
use App\Repositories\Employer\EmployerRepositoryInterface;
use App\Repositories\Job\JobRepositoryInterface;
use App\Repositories\Application\ApplicationRepositoryInterface;
use App\DTOs\Employer\EmployerDTO;
use App\DTOs\Employer\UpdateEmployerDTO;
use App\DTOs\Job\JobDTO;
use App\DTOs\Job\CreateJobDTO;
use App\DTOs\Job\UpdateJobDTO;
use App\DTOs\Application\ApplicationDTO;
use App\Enums\ApplicationStatusEnum;
use App\Exceptions\EmployerNotFoundException;
use App\Exceptions\EmployerNotApprovedException;
use App\Exceptions\JobNotFoundException;
use App\Exceptions\ApplicationNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployerService implements EmployerServiceInterface
{
    public function __construct(
        private EmployerRepositoryInterface $employerRepository,
        private JobRepositoryInterface $jobRepository,
        private ApplicationRepositoryInterface $applicationRepository,
    ) {}

    public function getMyProfile(): EmployerDTO
    {
        $employer = $this->getAuthenticatedEmployer();
        return $this->mapEmployerToDTO($employer);
    }

    public function updateMyProfile(UpdateEmployerDTO $dto): EmployerDTO
    {
        $employer = $this->getAuthenticatedEmployer();
        $data = array_filter($dto->toArray(), fn($value) => $value !== null);
        $this->employerRepository->update($employer->id, $data);
        $updatedEmployer = $this->employerRepository->findByUserId(Auth::id());
        return $this->mapEmployerToDTO($updatedEmployer);
    }

    public function createJob(CreateJobDTO $dto): JobDTO
    {
        $employer = $this->getAuthenticatedEmployerWithApprovalCheck();

        $data = [
            ...$dto->toArray(),
            'employer_id' => $employer->id,
        ];

        $job = $this->jobRepository->create($data);

        return $this->mapJobToDTO($job);
    }

    public function updateJob(int $jobId, UpdateJobDTO $dto): JobDTO
    {
        $employer = $this->getAuthenticatedEmployerWithApprovalCheck();

        $job = $this->jobRepository->find($jobId);

        if (!$job || $job->employer_id !== $employer->id) {
            throw new JobNotFoundException("Job with ID $jobId not found");
        }

        $data = array_filter($dto->toArray(), fn($value) => $value !== null);
        $this->jobRepository->update($jobId, $data);

        $updatedJob = $this->jobRepository->find($jobId);
        return $this->mapJobToDTO($updatedJob);
    }

    public function deleteJob(int $jobId): void
    {
        $employer = $this->getAuthenticatedEmployerWithApprovalCheck();

        $job = $this->jobRepository->find($jobId);

        if (!$job || $job->employer_id !== $employer->id) {
            throw new JobNotFoundException("Job with ID $jobId not found");
        }

        $this->jobRepository->delete($jobId);
    }

    public function getMyJobs(int $perPage = 15): LengthAwarePaginator
    {
        $employer = $this->getAuthenticatedEmployer();
        $jobs = $this->jobRepository->findByEmployerId($employer->id, $perPage);
        $jobs->getCollection()->transform(fn(Job $job) => $this->mapJobToDTO($job));

        return $jobs;
    }

    public function getMyJob(int $jobId): JobDTO
    {
        $employer = $this->getAuthenticatedEmployer();

        $job = $this->jobRepository->find($jobId);

        if (!$job || $job->employer_id !== $employer->id) {
            throw new JobNotFoundException("Job with ID $jobId not found");
        }

        return $this->mapJobToDTO($job);
    }




    public function getJobApplications(int $jobId, int $perPage = 15): LengthAwarePaginator
    {
        $employer = $this->getAuthenticatedEmployer();

        $job = $this->jobRepository->find($jobId);

        if (!$job || $job->employer_id !== $employer->id) {
            throw new JobNotFoundException("Job with ID $jobId not found");
        }

        $applications = $this->applicationRepository->findByJobId($jobId, $perPage);
        $applications->getCollection()->transform(fn(Application $app) => $this->mapApplicationToDTO($app));

        return $applications;
    }

    public function acceptApplication(int $applicationId): ApplicationDTO
    {
        $application = $this->applicationRepository->findById($applicationId);

        if (!$application) {
            throw new ApplicationNotFoundException("Application with ID $applicationId not found");
        }

        $job = $this->jobRepository->find($application->job_id);
        $employer = $this->getAuthenticatedEmployer();

        if (!$job || $job->employer_id !== $employer->id) {
            throw new JobNotFoundException("You are not authorized to accept this application");
        }

        $updatedApplication = $this->applicationRepository->updateStatus(
            $applicationId,
            ApplicationStatusEnum::ACCEPTED
        );

        return $this->mapApplicationToDTO($updatedApplication);
    }

    public function rejectApplication(int $applicationId): ApplicationDTO
    {
        $application = $this->applicationRepository->findById($applicationId);

        if (!$application) {
            throw new ApplicationNotFoundException("Application with ID $applicationId not found");
        }

        $job = $this->jobRepository->find($application->job_id);
        $employer = $this->getAuthenticatedEmployer();

        if (!$job || $job->employer_id !== $employer->id) {
            throw new JobNotFoundException("You are not authorized to reject this application");
        }

        $updatedApplication = $this->applicationRepository->updateStatus(
            $applicationId,
            ApplicationStatusEnum::REJECTED
        );

        return $this->mapApplicationToDTO($updatedApplication);
    }

    private function mapApplicationToDTO(Application $application): ApplicationDTO
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

    private function mapEmployerToDTO(Employer $employer): EmployerDTO
    {
        return new EmployerDTO(
            id: $employer->id,
            user_id: $employer->user_id,
            company_name: $employer->company_name,
            company_logo: $employer->company_logo,
            company_description: $employer->company_description,
            company_website: $employer->company_website,
            company_location: $employer->company_location,
            phone: $employer->phone,
            is_approved: $employer->is_approved ?? false,
            created_at: $employer->created_at,
            updated_at: $employer->updated_at,
        );
    }

    private function getAuthenticatedEmployer(): Employer
    {
        $employer = $this->employerRepository->findByUserId(Auth::id());
        if (!$employer) {
            throw new EmployerNotFoundException('Employer profile not found');
        }
        return $employer;
    }

    private function getAuthenticatedEmployerWithApprovalCheck(): Employer
    {
        $employer = $this->getAuthenticatedEmployer();
        if (!$employer->is_approved) {
            throw new EmployerNotApprovedException();
        }
        return $employer;
    }
}
