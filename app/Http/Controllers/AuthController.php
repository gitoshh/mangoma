<?php

namespace App\Http\Controllers;

use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * Authenticates user.
     *
     * @throws ValidationException
     *
     * @return JsonResponse
     */
    public function authenticate()
    {
        $this->validate($this->request, User::$loginRules);

        // Find the user by email
        $user = User::where('email', $this->get('email'))->first();

        if (is_null($user)) {
            return response()->json([
                'message' => 'error',
                'data'    => 'Email does not exist',
            ], 400);
        }

        // Verify the password and generate the token
        if (Hash::check($this->get('password'), $user->password)) {
            $token = $this->jwt($user);
            $user->token = $token;
            $user->update();

            return response()->json([
                'token' => $token,
            ], 200);
        }

        // Bad Request response
        return response()->json([
            'error' => 'Email or password is wrong.',
        ], 400);
    }

    /**
     * Encodes user id into jwt token.
     *
     * @param User $user
     *
     * @return string
     */
    public function jwt(User $user)
    {
        $token = [
            'iss' => 'gitoshh/mangoma',
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + 60 * 60,
        ];

        return JWT::encode($token, getenv('JWT_TOKEN'));
    }

    /**
     * Removes user token from the table.
     */
    public function logout()
    {
        $user = User::find(Auth::user()->getAuthIdentifier());
        $user->token = null;
        if ($user->update()) {
            // Bad Request response
            return response()->json([
                'message' => 'success',
                'data'    => 'Logged out successfully',
            ], 200);
        }
    }
}
