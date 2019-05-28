<?php

namespace App\Http\Middleware;

use App\Role;
use App\User as UserModel;
use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as AuthManager;

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

            if ($token === null || $token === '') {
                return response()->json([
                    'message' => 'Unauthorized.',
                ], 401);
            }

            try {
                $decoded = JWT::decode($token, getenv('JWT_TOKEN'), ['HS256']);
            } catch (ExpiredException $e) {
                return response()->json([
                    'message' => 'Expired Token.',
                ], 401);
            } catch (SignatureInvalidException $e) {
                return response()->json([
                    'message' => 'Invalid Token.',
                ], 401);
            }

            $user = UserModel::find($decoded->sub);

            if ($user->token === $token && !empty($user->get())) {
                if (!$user->subscribed('Mangoma premium account') && !empty($user->roles()->first()) && ($user->roles()->first()['name'] === 'Premium')) {
                    $role = Role::where('name', 'Premium')->first();
                    $user->detachRole($role['id']);
                }
                AuthManager::setUser($user);

                return $next($request);
            }

            return response()->json([
                'message' => 'Unauthorized.',
            ], 401);
        }
    }
}
