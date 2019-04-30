<?php

namespace App\Http\Controllers;

use App\Domains\Entrust;
use App\Permission;
use App\Role;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EntrustController extends Controller
{
    private $entrustDomain;

    /**
     * EntrustController constructor.
     *
     * @param Request $request
     * @param Entrust $entrustDomain
     */
    public function __construct(Request $request, Entrust $entrustDomain)
    {
        parent::__construct($request);
        $this->entrustDomain = $entrustDomain;
    }

    /**
     * Add a new role.
     *
     * @throws ValidationException
     *
     * @return JsonResponse
     */
    public function newRole()
    {
        $this->validate($this->request, Role::$rules);

        $payload = $this->request->only('name', 'display', 'description');
        $response = $this->entrustDomain->newRole(
            $payload['name'],
            $payload['display'],
            $payload['description']
        );

        return response()->json([
            'message' => 'success',
            'data'    => $response,
        ]);
    }

    /**
     * Add a new permission.
     *
     * @throws ValidationException
     *
     * @return JsonResponse
     */
    public function newPermission()
    {
        $this->validate($this->request, Permission::$rules);

        $payload = $this->request->only('name', 'display', 'description');
        $response = $this->entrustDomain->newPermission(
            $payload['name'],
            $payload['display'],
            $payload['description']
        );

        return response()->json([
            'message' => 'success',
            'data'    => $response,

        ]);
    }

    /**
     * Attaches permissions to a role.
     *
     * @return JsonResponse
     */
    public function addPermissions()
    {
        $payload = $this->request->only('roleId', 'permissions');
        $role = Role::where('id', $payload['roleId'])->first();
        if ($role->attachPermissions($payload['permissions'])) {
            return response()->json([
                'message' => 'success',
                'data'    => 'Permissions added successfully',
            ], 200);
        }
    }

    /**
     * Attaches roles to a user.
     *
     * @return JsonResponse
     */
    public function addRole()
    {
        $payload = $this->request->only('userId', 'roleId');
        $user = User::where('id', $payload['userId'])->first();
        if ($user->attachRole($payload['roleId'])) {
            return response()->json([
                'message' => 'success',
                'data'    => 'Roles added successfully',
            ], 200);
        }
    }
}
