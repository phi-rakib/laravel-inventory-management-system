<?php

namespace App\Providers;

use App\Repositories\AuthRepository;
use App\Repositories\IAuthRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\IResourceRepository;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Repositories\ProductResourceRepository;
use App\Repositories\CategoryResourceRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IAuthRepository::class, AuthRepository::class);

        $this->app
            ->when(CategoryController::class)
            ->needs(IResourceRepository::class)
            ->give(function () {
                return new CategoryResourceRepository();
            });

        $this->app
            ->when(ProductController::class)
            ->needs(IResourceRepository::class)
            ->give(function () {
                return new ProductResourceRepository();
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
