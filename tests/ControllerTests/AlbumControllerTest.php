<?php


class AlbumControllerTest extends BaseTest
{
    public function testFetchAlbumsSuccessfully(): void
    {
        $params='?title=new%20album&artistes=jhene&releaseDate=12-12-2014';
        $this->get('/album'.$params, $this->headers);
        $this->assertResponseOk();
        $this->assertContains('success', $this->response->getContent());
    }

    public function testCreateNewAlbumSuccessfully(): void
    {
        $payload = [
            'title'       => 'Something new',
            'releaseDate' => '05-05-2016',
            'artistes'    => 'lucas',
        ];
        $this->post('/album', $payload, $this->headers);
        $this->assertResponseOk();
    }

    public function testCreateNewAlbumValidationFailure(): void
    {
        $payload = [
            'title'       => 'Something new',
        ];
        $this->post('/album', $payload, $this->headers);
        $this->assertResponseStatus(422);
    }

    public function testAddSongSuccess(): void
    {
        $payload = [
            'musicId' => 1,
        ];
        $this->post('/album/1/song', $payload, $this->headers);
        $this->assertResponseOk();
    }

    public function testUpdateAlbumSuccessfully(): void
    {
        $payload = [
            'title'       => 'Another Something',
            'releaseDate' => '06-06-2016',
            'artistes'    => 'lucasi',
        ];
        $this->put('/album/1', $payload, $this->headers);
        $this->assertResponseOk();
    }

    public function testUpdateAlbumFailureNotFound(): void
    {
        $payload = [
            'title'       => 'Another Something',
            'releaseDate' => '06-06-2016',
            'artistes'    => 'lucasi',
        ];
        $this->put('/album/5', $payload, $this->headers);
        $this->assertContains('Album not found', $this->response->getContent());
    }


    public function testDeleteAlbumSuccessfully(): void
    {
        $this->delete('/album/1', [] , $this->headers);
        $this->assertResponseOk();
    }

    public function testDeleteAlbumFailureNotFound(): void
    {
        $this->delete('/album/5', [], $this->headers);
        $this->assertContains('Album not found', $this->response->getContent());
    }

}