<?php

namespace App\Providers;

use App\Repositories\Bases\BaseContactRepository;
use App\Repositories\Bases\BaseFileRepository;
use App\Repositories\DB\ContactRepository;
use App\Repositories\DB\FileRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BaseContactRepository::class, ContactRepository::class);
        $this->app->bind(BaseFileRepository::class, FileRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
    }
}
