<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\FilesystemServiceProvider;

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
}
