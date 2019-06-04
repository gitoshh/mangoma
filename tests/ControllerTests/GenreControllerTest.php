<?php


class GenreControllerTest extends BaseTest
{
    public function testCreateGenreSuccessfully(): void
    {
        $payload = ['name' => 'rnb'];
        $this->post('/genre', $payload, $this->headers);
        $this->assertResponseOk();
    }

    public function testCreateGenreFailureValidation(): void
    {
        $payload = [];
        $this->post('/genre', $payload, $this->headers);
        $this->assertContains('The name field is required', $this->response->getContent());
    }

    public function testFetchGenreSuccessfully(): void
    {
        $this->get('/genre', $this->headers);
        $this->assertResponseOk();
    }

    public function testSearchMusicGenreSuccessfully(): void
    {
        $this->get('/genre?name=hiphop', $this->headers);
        $this->assertResponseOk();
        $this->assertContains('songs', $this->response->getContent());
    }

    public function testDeleteGenreSuccessfully(): void
    {
        $this->delete('/genre/1', [], $this->headers);
        $this->assertResponseOk();
        $this->assertContains('success', $this->response->getContent());
    }
}
