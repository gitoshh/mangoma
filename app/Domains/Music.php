<?php

namespace App\Domains;

use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Music as MusicModel;
use App\User as UserModel;
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
            throw new NotFoundException('Song not found.');
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
            throw new NotFoundException('Song not found.');
        }

        unlink($music->toArray()['location']);

        if ($music->delete()) {
            return true;
        }

        return false;
    }

    /**
     * Adds a many to many relationship for user and music.
     * @param int $musicId
     * @param int $userId
     * @param int $recommendedBy
     * @throws NotFoundException
     * @throws Exception
     */
    public function attachMusic(int $musicId, int $userId, int $recommendedBy): void
    {
        if ($userId === $recommendedBy) {
            throw new BadRequestException('You cannot recommend a song to yourself');
        }

        $user = UserModel::find($userId);
        if(!$user) {
            throw new NotFoundException('user not found');
        }

        $music = MusicModel::find($musicId);
        if(!$music) {
            throw new NotFoundException('song not found!');
        }
        $music->user()->attach($userId, ['recommended_by' => $recommendedBy]);

    }
}
