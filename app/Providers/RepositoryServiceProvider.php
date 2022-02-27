<?php

namespace App\Providers;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Repositories\CategoryResourceRepository;
use App\Repositories\IResourceRepository;
use App\Repositories\ProductResourceRepository;
use App\Repositories\UserResourceRepository;
use App\Services\AuthService;
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
        // $this->app->bind(IAuthRepository::class, AuthRepository::class);

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

        $this->app
            ->when([UserController::class, AuthService::class])
            ->needs(IResourceRepository::class)
            ->give(function () {
                return new UserResourceRepository();
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
