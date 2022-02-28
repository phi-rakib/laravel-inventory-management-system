<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Repositories\IRepository;
use App\Repositories\UserRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\UserController;
use App\Repositories\CategoryRepository;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

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
            ->needs(IRepository::class)
            ->give(function () {
                return new CategoryRepository();
            });

        $this->app
            ->when(ProductController::class)
            ->needs(IRepository::class)
            ->give(function () {
                return new ProductRepository();
            });

        $this->app
            ->when([UserController::class, AuthService::class])
            ->needs(IRepository::class)
            ->give(function () {
                return new UserRepository();
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
