<?php

namespace App\Http\Middleware;

use App\User as UserModel;
use Closure;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $token = $request->header('token');
        if ($token === null) {
            return response('Unauthorized.', 401);
        }
        $decoded = JWT::decode($token, getenv('JWT_TOKEN'), ['HS256']);
        $user = UserModel::find($decoded->sub);

        if (!empty($decoded->first())) {
            Auth::setUser($user);

            return $next($request);
        }

        return response('Unauthorized.', 401);
    }
}
