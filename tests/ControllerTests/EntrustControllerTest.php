<?php

class EntrustControllerTest extends BaseTest
{
    public function testCreateTestRoleSuccessfully(): void
    {
        $role = [
            'name'         => 'Test',
            'display_name' => 'test',
            'description'  => 'test role',
        ];

        $this->post('/admin/role', $role, $this->headers);
        $this->assertResponseOk();
    }

    public function testCreateTestRoleFailureValidation(): void
    {
        $role = [
            'display_name' => 'test',
            'description'  => 'test role',
        ];

        $this->post('/admin/role', $role, $this->headers);
        $this->assertResponseStatus(422);
        $this->assertContains('The name field is required', $this->response->getContent());
    }

    public function testAddPermissionSuccessfully(): void
    {
        $permission = [
            'name'         => 'add-songs',
            'display_name' => 'Add song',
            'description'  => 'Permission to add new songs',
        ];

        $this->post('/admin/permission', $permission, $this->headers);
        $this->assertResponseOk();
    }

    public function testAddPermissionFailureValidation(): void
    {
        $permission = [
            'display_name' => 'Add song',
            'description'  => 'Permission to add new songs',
        ];

        $this->post('/admin/permission', $permission, $this->headers);
        $this->assertResponseStatus(422);
        $this->assertContains('The name field is required', $this->response->getContent());
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

        $this->post('/admin/permission', $permission, $this->headers);
        $this->post('/admin/attach/permission', $rolePermissionPayload, $this->headers);
        $this->assertResponseOk();
    }
}
