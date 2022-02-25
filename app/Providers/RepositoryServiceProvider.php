<?php

namespace App\Providers;

use App\Http\Controllers\CategoryController;
use App\Repositories\CategoryResourceRepository;
use App\Repositories\IResourceRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app
            ->when(CategoryController::class)
            ->needs(IResourceRepository::class)
            ->give(function () {
                return new CategoryResourceRepository();
            });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
