<?php

namespace App\Providers;

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Repositories\BrandRepository;
use App\Repositories\BrandRepositoryInterface;
use App\Repositories\CategoryRepository;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
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
        $this->app
            ->when([AuthService::class])
            ->needs(UserRepositoryInterface::class)
            ->give(function () {
                return new UserRepository(new User);
            });

        $this->app
            ->when([UserController::class])
            ->needs(UserRepositoryInterface::class)
            ->give(function () {
                return new UserRepository(new User);
            });

        $this->app
            ->when([BrandController::class])
            ->needs(BrandRepositoryInterface::class)
            ->give(function () {
                return new BrandRepository(new Brand);
            });

        $this->app
            ->when(CategoryController::class)
            ->needs(CategoryRepositoryInterface::class)
            ->give(function () {
                return new CategoryRepository(new Category());
            });

        $this->app
            ->when(ProductController::class)
            ->needs(ProductRepositoryInterface::class)
            ->give(function () {
                return new ProductRepository(new Product());
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
