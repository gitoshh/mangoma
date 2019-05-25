<?php


class UsersControllerTest extends BaseTest
{
    public $payload;

    public function setUp(): void
    {
        parent::setUp();

        $this->payload = [
            'name'     => 'Godwin Gitonga',
            'email'    => 'godwingitonga@gmail.com',
            'password' => '@123123A',
        ];
    }

    public function testUserCreationSuccessful(): void
    {
        $this->post('/users', $this->payload);
        $this->assertResponseOk();
    }

    public function testUserValidationError(): void
    {
        unset($this->payload['email']);
        $this->payload['email'] = 'test.user@gmail.com';
        $this->post('/users', $this->payload);
        $this->assertResponseStatus(422);
        $this->assertContains('The email field already exists', $this->response->getContent());
    }
}
