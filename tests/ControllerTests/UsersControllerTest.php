<?php


class UsersControllerTest extends BaseTest
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testUserCreationSuccessful(): void
    {
        $payload = [
            'name'     => 'Godwin Gitonga',
            'email'    => 'godwingitonga87@gmail.com',
            'password' => '123123123',
        ];
        $this->post('/users', $payload, $this->headers);
        $this->assertResponseOk();
    }
}