<?php

namespace App\Domains;

use App\Album as AlbumModel;
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
     * Updates album details.
     *
     * @param int         $id
     * @param string|null $title
     * @param string|null $releaseDate
     * @param array|null  $artistes
     *
     * @return array
     */
    public function updateAlbum(
        int $id,
        string $title = null,
        string $releaseDate = null,
        array $artistes = null
    ): array {
        $album = AlbumModel::find($id);
        if ($title) {
            $album->title = $title;
        }
        if ($releaseDate) {
            $album->releaseDate = $releaseDate;
        }
        if ($artistes) {
            $album->artistes = $artistes;
        }
        $album->update();

        return $album;
    }

    /**
     * Delete album by id.
     *
     * @param int $id
     */
    public function deleteAlbum(int $id): void
    {
        $album = AlbumModel::find($id);
        $album->delete();
    }
}
