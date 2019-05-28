<?php

namespace App\Providers;

use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application Services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('filesystem', static function ($app) {
            return $app->loadComponent('filesystems', FilesystemServiceProvider::class, 'filesystem');
        });
    }

    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
