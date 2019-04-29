<?php

class AuthControllerTest extends BaseTest
{
    public $payload;

    public function setUp(): void
    {
        parent::setUp();

        $this->payload = [
            'email'    => 'test.user@gmail.com',
            'password' => 'password',
        ];
    }

    public function testUserSignUpSuccessful(): void
    {
        $this->post('/auth/login', $this->payload);
        $this->assertResponseOk();
        $this->assertContains('token', $this->response->getContent());
    }

    public function testUserUnauthorizedError(): void
    {
        unset($this->payload['email']);
        $this->payload['email'] = 'another.user@gmail.com';
        $this->post('/auth/login', $this->payload);
        $this->assertResponseStatus(400);
    }
}
