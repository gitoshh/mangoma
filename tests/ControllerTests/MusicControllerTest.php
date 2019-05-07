<?php

use Illuminate\Http\UploadedFile;

class MusicControllerTest extends BaseTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loginPayload = [
            'email'    => 'test.user@gmail.com',
            'password' => 'password',
        ];
    }

    public function testArtisteCanAddMusicSuccessfully()
    {
        $payload = [
            'title' => 'another new song',
        ];
        $song = [
            'song'  => UploadedFile::fake()->create('music.mp3', 1300),
        ];

        $this->post('/auth/login', $this->loginPayload);
        $this->headers['token'] = json_decode($this->response->getContent())->token;
        $this->headers['Content-Type'] = 'multipart/form-data';
        $this->call('POST', '/music', $payload, [], $song, $this->transformHeadersToServerVars($this->headers));
        $this->assertResponseOk();
    }
}
