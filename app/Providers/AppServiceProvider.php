<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\BreadcrumbService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // Tùy chỉnh text phân trang
        Paginator::defaultView('pagination::bootstrap-5');
        Paginator::defaultSimpleView('pagination::bootstrap-5');

        // Tùy chỉnh text
        Paginator::useBootstrap();

        // Tùy chỉnh text hiển thị
        Paginator::$defaultView = 'pagination::bootstrap-5';
        Paginator::$defaultSimpleView = 'pagination::bootstrap-5';
    }
}
