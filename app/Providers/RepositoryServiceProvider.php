<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Repositories
use App\Repositories\Job\JobRepositoryInterface;
use App\Repositories\Job\JobRepository;
use App\Repositories\Application\ApplicationRepositoryInterface;
use App\Repositories\Application\ApplicationRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Category\CategoryRepository;

// Services
//use App\Services\User\UserServiceInterface;
//use App\Services\User\UserService;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Repositories
        $this->app->bind(JobRepositoryInterface::class, JobRepository::class);
        $this->app->bind(ApplicationRepositoryInterface::class, ApplicationRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);

        // Services
      //  $this->app->bind(UserServiceInterface::class, UserService::class);
        // $this->app->bind(EmployerServiceInterface::class, EmployerService::class);
        // $this->app->bind(JobServiceInterface::class, JobService::class);
        // $this->app->bind(CandidateServiceInterface::class, CandidateService::class);
        // $this->app->bind(ApplicationServiceInterface::class, ApplicationService::class);
        // $this->app->bind(SkillServiceInterface::class, SkillService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
