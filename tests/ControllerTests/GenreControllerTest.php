<?php


class GenreControllerTest extends BaseTest
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

    public function testCreateGenreSuccessfully(): void
    {
        $payload = ['name' => 'rnb'];
        $this->post('/genre', $payload, ['token' => $this->token]);
        $this->assertResponseOk();
    }

    public function testFetchGenreSuccessfully(): void
    {
        $this->get('/genre', ['token' => $this->token]);
        $this->assertResponseOk();
    }

    public function testSearchMusicGenreSuccessfully(): void
    {
        $this->get('/genre?name=hiphop', ['token' => $this->token]);
        $this->assertResponseOk();
        $this->assertContains('songs', $this->response->getContent());
    }
}