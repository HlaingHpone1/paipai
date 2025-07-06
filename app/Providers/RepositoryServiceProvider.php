<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Organization\OrganizationRepositoryInterface;
use App\Repositories\Organization\OrganizationRepository;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->singleton(OrganizationRepositoryInterface::class, OrganizationRepository::class);
    }
}
