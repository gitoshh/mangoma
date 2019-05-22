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
        //
    }

    /**
     * Boot the authentication Services for the application.
     *
     * @return void
     * @throws Exception
     */
    public function boot(): void
    {
        Cashier::useCurrency('eur', '€');
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->input('api_token')) {
                return User::where('api_token', $request->input('api_token'))->first();
            }
        });
    }
}
