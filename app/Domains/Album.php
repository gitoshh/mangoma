<?php

namespace App\Domains;

use App\Album as AlbumModel;

class Album
{
    /**
     * Creates new album.
     *
     * @param string $title
     * @param string $releaseDate
     * @param array $artistes
     * @return array
     */
    public function newAlbum(
        string $title,
        string $releaseDate,
        array $artistes
    ): array {
        $album = new AlbumModel();
        $album->title = $title;
        $album->releaseDate = $releaseDate;
        $album->artistes = $artistes;
        if ($album->save()) {
            return $album->toArray();
        }

        return [];
    }

    /**
     * Updates album details.
     *
     * @param int $id
     * @param string|null $title
     * @param string|null $releaseDate
     * @param array|null $artistes
     * @return array
     */
    public function updateAlbum(
        int $id,
        string $title =  null,
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
