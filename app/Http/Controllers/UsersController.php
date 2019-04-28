<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Domains\User as UserDomain;

class UsersController extends Controller
{
    /**
     * @var UserDomain
     */
    private $userDomain;

    /**
     * Create a new controller instance.
     *
     * @param Request    $request
     * @param UserDomain $userDomain
     */
    public function __construct(Request $request, UserDomain $userDomain)
    {
        Parent::__construct($request);
        $this->userDomain = $userDomain;
    }

    /**
     * Adds a new user.
     */
    public function new()
    {
        try {
            $this->validate($this->request, [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                    'message' => 'error',
                    'data'    => $e->getMessage(),
                    ], 422
            );
        }

        $response = $this->userDomain->newUser(
            $this->get('name'),
            $this->get('email'),
            $this->get('password')
        );

        if(!empty($response)) {
            return response()->json([
                'message' => 'success',
                'data'    => $response,
            ], 200);
        }

        return response()->json([
            'message' => 'error'
        ], 500);
    }
}