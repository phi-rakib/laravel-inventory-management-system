<?php

namespace App\Providers;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Repositories\CategoryResourceRepository;
use App\Repositories\IResourceRepository;
use App\Repositories\ProductResourceRepository;
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
