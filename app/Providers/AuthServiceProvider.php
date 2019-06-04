<?php

namespace App\Providers;

use App\User;
use Exception;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application Services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Boot the authentication Services for the application.
     *
     * @throws Exception
     *
     * @return void
     */
    public function boot(): void
    {
        Cashier::useCurrency('eur', '€');

        $this->app['auth']->viaRequest('api', static function ($request) {
            if ($request->input('api_token')) {
                return User::where('api_token', $request->input('api_token'))->first();
            }
        });
    }
}
