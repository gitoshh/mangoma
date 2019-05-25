<?php

class AuthControllerTest extends BaseTest
{
    public $payload;

    public function setUp(): void
    {
        parent::setUp();

        $this->payload = [
            'email'    => 'test.user@gmail.com',
            'password' => 'A123123@',
        ];
    }

    public function testUserSignInSuccessful(): void
    {
        $this->post('/auth/login', $this->payload);
        $this->assertResponseOk();
        $this->assertContains('token', $this->response->getContent());
    }

    public function testUserSignInBadRequestError(): void
    {
        unset($this->payload['email']);
        $this->payload['email'] = 'another.user@gmail.com';
        $this->post('/auth/login', $this->payload);
        $this->assertResponseStatus(400);
        $this->assertContains('Email does not exist', $this->response->getContent());
    }

    public function testUserUnauthorisedError(): void
    {
        $payload = ['name' => 'hiphop'];
        $expiredToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJnaXRvc2hoXC9tYW5nb21hIiwic3ViIjoxLCJpYXQiOjE1NTg3NTk2MDcsImV4cCI6MTU1ODc2MzIwN30.3OXL_5CJgjaD3D1AEV7sBRYCHjbPNu_JIJWEseMAqnk';
        $this->post('/genre', $payload, ['token' => $expiredToken]);
        $this->assertResponseStatus(401);
        $this->assertContains('Expired Token.', $this->response->getContent());
    }
}
