<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\BannerService;
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\BannerServiceInterface;
use App\Services\Contracts\ProductCategoryServiceInterface;
use App\Services\Contracts\ProductServiceInterface;
use App\Services\Contracts\ShopServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Services\ProductCategoryService;
use App\Services\ProductService;
use App\Services\ShopService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AuthServiceInterface::class, AuthService::class);
        $this->app->singleton(ProductCategoryServiceInterface::class, ProductCategoryService::class);
        $this->app->singleton(ProductServiceInterface::class, ProductService::class);
        $this->app->singleton(UserServiceInterface::class, UserService::class);
        $this->app->singleton(ShopServiceInterface::class, ShopService::class);
        $this->app->singleton(BannerServiceInterface::class, BannerService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
