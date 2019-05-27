<?php

class MusicControllerTest extends BaseTest
{
    /**
     * @var []
     */
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

    public function testArtisteCanUpdateSongDetails(): void
    {
        $payload = [
            'title'    => 'Just Another new song',
        ];

        $this->headers['token'] = $this->token;
        $this->headers['Content-Type'] = 'multipart/form-data';
        $this->call('PUT', '/music/1', $payload, [], [], $this->transformHeadersToServerVars($this->headers));
        $this->assertResponseStatus(202);
    }
}
