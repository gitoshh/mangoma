<?php

use Illuminate\Http\UploadedFile;

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
        $this->token = json_decode($this->response->getContent())->token;
    }

    public function testArtisteCanAddMusicSuccessfully(): void
    {
        $payload = [
            'title'    => 'Another new song',
            'artistes' => 'Sean, Jhene',
            'genreId'  => 1
        ];
        $song = [
            'song'  => UploadedFile::fake()->create('music.mp3', 1300),
        ];

        $this->headers['token'] = $this->token;
        $this->headers['Content-Type'] = 'multipart/form-data';
        $this->call('POST', '/music', $payload, [], $song, $this->transformHeadersToServerVars($this->headers));
        $this->assertResponseOk();
    }

    public function testArtisteCanUpdateSongDetails(): void
    {
        $payload = [
            'title'    => 'Just Another new song',
        ];

        $this->headers['token'] = $this->token;
        $this->headers['Content-Type'] = 'multipart/form-data';
        $this->call('PUT', '/music/1', $payload, [], [], $this->transformHeadersToServerVars($this->headers));
        $this->assertResponseOk();
    }
}
