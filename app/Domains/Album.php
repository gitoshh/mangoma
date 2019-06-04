<?php

namespace App\Domains;

use App\Album as AlbumModel;
use App\Exceptions\NotFoundException;
use App\Music as MusicModel;
use Carbon\Carbon;

class Album
{
    /**
     * Fetch all albums.
     *
     * @param array|null $searchParams
     *
     * @return array
     */
    public function getAllAlbums(?array $searchParams = null): array
    {
        if ($searchParams) {
            $albums = AlbumModel::select('*');
            foreach ($searchParams as $key => $value) {
                $albums = $albums->where($key, 'LIKE', '%'.$value.'%');
            }
            $albums = $albums->get();
        } else {
            $albums = AlbumModel::all();
        }

        $response = [];

        foreach ($albums as $item) {
            $response[] = [
                'name'        => $item->name,
                'artistes'    => $item->artistes,
                'releaseDate' => $item->releaseDate,
                'songs'       => $item->music()->get()->toArray(),
            ];
        }

        return $response;
    }

    /**
     * Creates new album.
     *
     * @param string $title
     * @param string $releaseDate
     * @param string $artistes
     *
     * @return array
     */
    public function newAlbum(
        string $title,
        string $releaseDate,
        string $artistes
    ): array {
        $album = new AlbumModel();
        $album->title = $title;
        $album->releaseDate = Carbon::parse($releaseDate)->toDateString();
        $album->artistes = $artistes;
        if ($album->save()) {
            return $album->toArray();
        }

        return [];
    }

    /**
     * Add an existing song to album.
     *
     * @param int $albumId
     * @param int $songId
     *
     * @throws NotFoundException
     *
     * @return array
     */
    public function addSong(int $albumId, int $songId): array
    {
        $song = MusicModel::find($songId);
        if (empty($song)) {
            throw new NotFoundException('Song not found');
        }
        $song->album_id = $albumId;

        if ($song->update()) {
            return $song->toArray();
        }
    }

    /**
     * Updates album details.
     *
     * @param int $id
     * @param array $updateDetails
     *
     * @throws NotFoundException
     *
     * @return array
     */
    public function updateAlbum(
        int $id,
        array $updateDetails
    ): array {
        $album = AlbumModel::find($id);

        if (empty($album)) {
            throw new NotFoundException('Album not found');
        }
        if ($title = $updateDetails['title']?? null) {
            $album->title = $title;
        }

        if ($releaseDate = $updateDetails['releaseDate']?? null) {
            $album->releaseDate = Carbon::parse($releaseDate)->toDateString();
        }

        if ($artistes = $updateDetails['artistes']?? null) {
            $album->artistes = $artistes;
        }
        $album->update();

        return $album->toArray();
    }

    /**
     * Delete album by id.
     *
     * @param int $id
     *
     * @throws NotFoundException
     */
    public function deleteAlbum(int $id): void
    {
        $album = AlbumModel::find($id);
        if (empty($album)) {
            throw new NotFoundException('Album not found');
        }
        $album->delete();
    }
}
