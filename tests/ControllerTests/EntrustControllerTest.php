<?php

class EntrustControllerTest extends BaseTest
{
    /**
     * var []
     */
    private $permission;

    public function setUp(): void
    {
        parent::setUp();

        $this->permission = [
            'name'         => 'add-songs',
            'display_name' => 'Add song',
            'description'  => 'Permission to add new songs',
        ];

        $this->loginPayload = [
            'email'    => 'test.user@gmail.com',
            'password' => 'password',
        ];
    }

    public function testCreateAdminRoleSuccessfully()
    {
        $role = [
            'name'         => 'Artiste',
            'display_name' => 'Artiste',
            'description'  => 'Artiste role',
        ];
        $this->post('/auth/login', $this->loginPayload);
        $this->headers['token'] = json_decode($this->response->getContent())->token;
        $this->post('/admin/role', $role, $this->headers);
        $this->assertResponseOk();
    }

    public function testAddPermissionSuccessfully()
    {
        $permission = [
            'name'         => 'add-songs',
            'display_name' => 'Add song',
            'description'  => 'Permission to add new songs',
        ];
        $this->post('/auth/login', $this->loginPayload);
        $this->headers['token'] = json_decode($this->response->getContent())->token;
        $this->post('/admin/permission', $permission, $this->headers);
        $this->assertResponseOk();
    }

    public function testAttachUserRoleScuccessfully()
    {
        $userCredentials = [
            'name'     => 'another user',
            'email'    => 'another.user@gmail.com',
            'password' => '123123123'
        ];
        $userRolePayload = [
            'userId' => 2,
            'roleId' => 1,
        ];

        $this->post('/auth/login', $this->loginPayload);
        $this->headers['token'] = json_decode($this->response->getContent())->token;
        $this->post('/users', $userCredentials);
        $this->post('/admin/attach/role', $userRolePayload, $this->headers);
        $this->assertResponseOk();
    }

    public function testAttachRolePermissionSuccessfully()
    {
        $permission = [
            'name'         => 'another user',
            'display_name' => 'another.user@gmail.com',
            'description'  => '123123123'
        ];
        $rolePermissionPayload = [
            'userId' => 2,
            'permissions' => [2],
        ];

        $this->post('/auth/login', $this->loginPayload);
        $this->headers['token'] = json_decode($this->response->getContent())->token;
        $this->post('/admin/permission', $permission, $this->headers);
        $this->post('/admin/attach/permission', $rolePermissionPayload, $this->headers);
        $this->assertResponseOk();
    }
}
