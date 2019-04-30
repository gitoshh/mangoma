<?php

namespace App\Http\Middleware;

use App\User as UserModel;
use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var Auth
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param Auth $auth
     *
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request     $request
     * @param Closure     $next
     * @param string|null $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($this->auth->guard($guard)->guest()) {
            $token = $request->header('token');

            if ($token === null) {
                return response('Unauthorized.', 401);
            }

            try {
                $decoded = JWT::decode($token, getenv('JWT_TOKEN'), ['HS256']);
            } catch (ExpiredException $e) {
                return response('Expired Token.', 401);
            }

            $user = UserModel::find($decoded->sub);
            $storedToken = $user->first('token')->toArray()['token'];

            if (!empty($user->first()) && $storedToken === $token) {
                \Illuminate\Support\Facades\Auth::setUser($user);

                return $next($request);
            }

            return response('Unauthorized.', 401);
        }
    }
}
