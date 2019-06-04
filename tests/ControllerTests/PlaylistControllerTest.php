<?php


class PlaylistControllerTest extends BaseTest
{
    public function testGetPlaylistsSuccessfully(): void
    {
        $this->get('/playlist', $this->headers);
        $this->assertResponseOk();
    }

    public function testCreatePlaylistSuccessfully(): void
    {
        $this->post('/playlist', [
            'title' => 'My first',
        ], $this->headers);
        $this->assertResponseOk();
    }

    public function testCreatePlaylistValidationFailure(): void
    {
        $this->post('/playlist', [], $this->headers);
        $this->assertContains('The title field is required', $this->response->getContent());
    }

    public function testSharePlaylistSuccessfully(): void
    {
        $this->post('/playlist/1/share', ['userId' => 2], $this->headers);
        $this->assertResponseOk();
    }

    public function testSharePlaylistFailureUserMissing(): void
    {
        $this->post('/playlist/1/share', ['userId' => 10], $this->headers);
        $this->assertContains('User not found', $this->response->getContent());
    }

    public function testDeletePlaylistSuccessfully(): void
    {
        $this->delete('/playlist/1', [], $this->headers);
        $this->assertResponseOk();
    }

    public function testDeletePlaylistFailureNotFound(): void
    {
        $this->delete('/playlist/10', [], $this->headers);
        $this->assertContains('Playlist not found', $this->response->getContent());
    }
}
