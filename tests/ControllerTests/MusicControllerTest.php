<?php

use App\Services\GoogleCloudStorage\GoogleStorageAdapter;
use Illuminate\Http\UploadedFile;

class MusicControllerTest extends BaseTest
{
    private $mockedRepo;

    public function setUp(): void
    {
        parent::setUp();
        $this->mockedRepo = Mockery::mock(GoogleStorageAdapter::class);
        app()->instance(GoogleStorageAdapter::class,
            $this->mockedRepo);
    }

    public function testAddMusicFailureValidation(): void
    {
        $payload = [
            'title'    => 'Just Another new song',
        ];
        $this->post('/music', $payload, $this->headers);
        $this->assertContains('The song field is required', $this->response->getContent());
    }

    public function testAddNewSongSuccessfully(): void
    {
        $this->mockedRepo->shouldReceive('write')->once()->andReturn(['path' => 'somePath']);

        $song = UploadedFile::fake()->create('music.mp3', 1);

        $payload = [
            'title'    => 'Just Another new song',
            'artistes' => 'Smith',
            'albumId'  => 1,
            'genreId'  => 1,
        ];
        $this->headers['Content-Type'] = 'multipart/form-data';
        $server = $this->transformHeadersToServerVars($this->headers);
        $this->call('POST', '/music', $payload, [], ['song' => $song ], $server);
        $this->assertResponseOk();
    }

    public function testDeleteSongSuccessfully(): void
    {
        $this->mockedRepo->shouldReceive('delete')->once()->andReturn(true);
        $this->delete('/music/1', [], $this->headers);
        $this->assertResponseOk();
    }


    public function testDeleteSongFailureNotFound(): void
    {
        $this->delete('/music/10', [], $this->headers);
        $this->assertContains('Song not found', $this->response->getContent());
    }

    public function testRecommendSongSuccessfully(): void
    {
        $this->post('/music/1/recommend', ['userId' => 2], $this->headers);
        $this->assertResponseOk();
    }

    public function testFetchRecommendedSongSuccessfully(): void
    {
        $this->get('/music/recommended', $this->headers);
        $this->assertResponseOk();
    }

    public function testAddCommentSuccessfully(): void
    {
        $this->post('/music/1/comment', ['comment' => 'yooo', 'rating' => 4], $this->headers);
        $this->assertResponseOk();
        $this->assertContains('success', $this->response->getContent());
    }

    public function testAddCommentFailureBadRequest(): void
    {
        $this->post('/music/1/comment', [], $this->headers);
        $this->assertContains('No ratings or comment provided', $this->response->getContent());
    }

    public function testDeleteCommentSuccessfully(): void
    {
        $this->post('/music/1/comment', ['comment' => 'yooo'], $this->headers);
        $this->delete('/music/1/comment/1', [], $this->headers);
        $this->assertResponseOk();

    }

    public function testGetSongsSuccessfully(): void
    {
        $this->get('/music', $this->headers);
        $this->assertResponseOk();
    }

    public function testAddFavouriteSuccessfully(): void
    {
        $this->post('/music/2/favourite', [], $this->headers);
        $this->assertResponseOk();
    }

    public function testAddFavouriteFailureSongExists(): void
    {
        $this->post('/music/1/favourite', [], $this->headers);
        $this->assertContains('Song already exists', $this->response->getContent());
    }

    public function testAddToPlaylistSuccessfully(): void
    {
        $this->post('/music/1/playlist', ['playlistId' => 1], $this->headers);
        $this->assertResponseOk();
    }

    public function testDownloadSongSuccessfully(): void
    {
        $song = UploadedFile::fake()->create('music.mp3', 1);
        $file = file_get_contents($song);
        $this->mockedRepo->shouldReceive('read')->once()->andReturn($file);
        $this->get('/music/1/download', $this->headers);
        $this->assertResponseOk();

    }

    public function testArtisteCanUpdateSongDetails(): void
    {
        $payload = [
            'title'    => 'Just Another new song',
            'artistes' => 'Smith',
            'albumId'  => 1,
            'genreId'  => 1,
        ];

        $song = UploadedFile::fake()->create('music.mp3', 1);

        $this->mockedRepo->shouldReceive('write')->once()->andReturn(['path' => 'some path']);
        $this->call('PUT', '/music/1', $payload, [], ['song' => $song], $this->transformHeadersToServerVars($this->headers));
        $this->assertResponseStatus(202);
    }

    public function testArtisteCanUpdateSongFailureNotFound(): void
    {
        $payload = [
            'title'    => 'Just Another new song',
            'artistes' => 'Smith',
            'albumId'  => 1,
            'genreId'  => 1,
        ];

        $song = UploadedFile::fake()->create('music.mp3', 1);

        $this->call('PUT', '/music/10', $payload, [], ['song' => $song], $this->transformHeadersToServerVars($this->headers));
        $this->assertContains('Song not found', $this->response->getContent());

    }
}
