<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Job\JobRepositoryInterface;
use App\Repositories\Job\JobRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */


//


   public function register(): void
{
$this->app->bind(JobRepositoryInterface::class, JobRepository::class);
}

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
