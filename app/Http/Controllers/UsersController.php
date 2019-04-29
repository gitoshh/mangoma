<?php

namespace App\Http\Controllers;

use App\Domains\User as UserDomain;
use App\User as UserModel;
use Illuminate\Http\Request;

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
        $this->validate($this->request, UserModel::$rules);

        $response = $this->userDomain->newUser(
            $this->get('name'),
            $this->get('email'),
            $this->get('password')
        );

        if (!empty($response)) {
            return response()->json([
                'message' => 'success',
                'data'    => $response,
            ], 200);
        }

        return response()->json([
            'message' => 'error',
        ], 500);
    }
}
