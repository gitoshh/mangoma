<?php

namespace App\Http\Controllers;

use App\Domains\User as UserDomain;
use App\User as UserModel;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
     * @throws ValidationException
     */
    public function new()
    {
        $this->validate($this->request, UserModel::$userRules);

        $response = $this->userDomain->newUser(
            $this->get('name'),
            $this->get('email'),
            $this->get('password')
        );

        if (!empty($response)) {
            return response()->json([
                'message' => 'success',
                'data'    => $response,
            ]);
        }

        return response()->json([
            'message' => 'error',
        ], 500);
    }
}
