<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        Gate::define('isSelf', function ($auth_user, $req) {
            return $auth_user->_id == $req->_id || $auth_user->_id == $req->user_id;
        });


        $this->app['auth']->viaRequest('api', function ($request) {
            $access_token = $request->input('access_token');
            if(empty($access_token)){
                $auth_header = $request->header('Authorization');
                $auth_header = explode(' ', $auth_header);
                $access_token = array_pop($auth_header);
            }

            if ($access_token) {
                return User::where('access_token', $access_token)
                            // ->where('access_token_expired', '>=', date('Y-m-d H:i:s'))
                            ->first();
            }
        });
    }
}
