<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Job\JobRepositoryInterface;
use App\Repositories\Job\JobRepository;
use App\Repositories\Application\ApplicationRepositoryInterface;
use App\Repositories\Application\ApplicationRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(JobRepositoryInterface::class, JobRepository::class);
        $this->app->bind(ApplicationRepositoryInterface::class, ApplicationRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
