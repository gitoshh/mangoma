<?php


class FavouriteControllerTest extends BaseTest
{
    private $token;

    public function setUp(): void
    {
        parent::setUp();
        $this->post('/auth/login', [
            'email'    => 'test.user@gmail.com',
            'password' => 'A123123@',
        ]);
        $this->token = json_decode($this->response->getContent(), true)['token'];
    }

    public function testUserCanAddFavouriteSong(): void
    {
        $this->post('/music/1/favourite', [], ['token' => $this->token]);
        $this->assertResponseOk();
    }

    public function testUserCanFetchFavouriteSongs(): void
    {
        $this->get('/favourite', ['token' => $this->token]);
        $this->assertResponseOk();
    }
}