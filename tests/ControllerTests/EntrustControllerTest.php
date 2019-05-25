<?php

class EntrustControllerTest extends BaseTest
{
    /**
     * var [].
     */
    private $permission;

    /**
     * @var string
     */
    private $token;

    public function setUp(): void
    {
        parent::setUp();

        $this->permission = [
            'name'         => 'add-songs',
            'display_name' => 'Add song',
            'description'  => 'Permission to add new songs',
        ];

        $this->post('/auth/login', [
            'email'    => 'test.user@gmail.com',
            'password' => 'A123123@',
        ]);
        $this->token = json_decode($this->response->getContent())->token;
    }

    public function testCreateAdminRoleSuccessfully(): void
    {
        $role = [
            'name'         => 'Test',
            'display_name' => 'test',
            'description'  => 'test role',
        ];

        $this->headers['token'] = $this->token;
        $this->post('/admin/role', $role, $this->headers);
        $this->assertResponseOk();
    }

    public function testAddPermissionSuccessfully(): void
    {
        $permission = [
            'name'         => 'add-songs',
            'display_name' => 'Add song',
            'description'  => 'Permission to add new songs',
        ];

        $this->headers['token'] = $this->token;
        $this->post('/admin/permission', $permission, $this->headers);
        $this->assertResponseOk();
    }

    public function testAttachUserRoleSuccessfully(): void
    {
        $userCredentials = [
            'name'     => 'another user',
            'email'    => 'another.user@gmail.com',
            'password' => 'A123123@',
        ];
        $userRolePayload = [
            'userId' => 2,
            'roleId' => 1,
        ];

        $this->headers['token'] = $this->token;
        $this->post('/users', $userCredentials);
        $this->post('/admin/attach/role', $userRolePayload, $this->headers);
        $this->assertResponseOk();
    }

    public function testAttachRolePermissionSuccessfully(): void
    {
        $permission = [
            'name'         => 'another user',
            'display_name' => 'another.user@gmail.com',
            'description'  => 'Just some description',
        ];
        $rolePermissionPayload = [
            'userId'      => 2,
            'permissions' => [2],
        ];

        $this->headers['token'] = $this->token;
        $this->post('/admin/permission', $permission, $this->headers);
        $this->post('/admin/attach/permission', $rolePermissionPayload, $this->headers);
        $this->assertResponseOk();
    }
}
