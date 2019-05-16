<?php

namespace App\Domains;

use App\Music as MusicModel;
use Exception;

class Music
{
    /**
     * Creates new music instance in the database.
     *
     * @param string $title
     * @param string $location
     * @param string $originalName
     * @param string $extension
     * @param string $uniqueName
     *
     * @param array $artistes
     * @param int $albumId
     * @return array
     */
    public function newMusic(
        string $title,
        string $location,
        string $originalName,
        string $extension,
        string $uniqueName,
        array $artistes,
        ?int $albumId
    ): array {
        $newMusic = MusicModel::create([
            'title'        => $title,
            'location'     => $location,
            'originalName' => $originalName,
            'extension'    => $extension,
            'uniqueName'   => $uniqueName,
            'artistes'     => serialize($artistes)
        ]);
        if ($albumId) {
            $newMusic->albumId = $albumId;
            $newMusic->save();
        }

        if (!empty($newMusic)) {
            return $newMusic->toArray();
        }

        return [];
    }

    /**
     * Updates song details.
     *
     * @param int   $id
     * @param array $updateDetails
     *
     * @throws Exception
     *
     * @return array
     */
    public function updateSong(int $id, array $updateDetails): array
    {
        $music = MusicModel::find($id);
        if (empty($music)) {
            throw new Exception('Song not found.');
        }
        foreach ($updateDetails as $key => $value) {
            if ($key === 'location') {
                unlink(public_path($music->toArray()['location']));
            }
            if ($key === 'artistes') {
                $music->artistes = serialize($value);
            } else {
                $music->$key = $value;
            }
        }
        if ($music->update()) {
            return $music->toArray();
        }

        return [];
    }

    /**
     * Removes song from music list.
     *
     * @param $id
     *
     * @throws Exception
     *
     * @return bool
     */
    public function removeSong(int $id): bool
    {
        $music = MusicModel::find($id);
        if (empty($music)) {
            throw new Exception('Song not found.');
        }

        unlink($music->toArray()['location']);

        if ($music->delete()) {
            return true;
        }

        return false;
    }
}
