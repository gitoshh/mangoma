<?php

namespace App\Domains;

use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Favourite;
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
     * @param string $artistes
     * @param int $genreId
     * @param int $albumId
     * @return array
     */
    public function newMusic(
        string $title,
        string $location,
        string $originalName,
        string $extension,
        string $uniqueName,
        string $artistes,
        int    $genreId,
        ?int $albumId
    ): array {
        $newMusic = MusicModel::create([
            'title'        => $title,
            'location'     => $location,
            'originalName' => $originalName,
            'extension'    => $extension,
            'uniqueName'   => $uniqueName,
            'artistes'     => $artistes,
            'genreId'      => $genreId
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

    /**
     * Fetch all songs with filters applied.
     *
     * @param array|null $filters
     * @return array
     */
    public function getSongs(?array $filters = null): array
    {
        $songs = MusicModel::select([
            'title',
            'location',
            'extension',
            'artistes'
        ]);
        if ($filters) {
            foreach ($filters as $key => $value) {
                switch ($key) {
                    case 'albumId':
                        $songs->where('album_id', $value);
                        break;
                    case 'title':
                        $songs->where('title', 'LIKE', '%'.$value.'%');
                        break;
                    case 'genreId':
                        $songs->where('genreId', $value);
                        break;
                    case 'artistes':
                        $artistes = explode(',', $value);
                        foreach ($artistes as $artiste) {
                            $songs->where('artistes', 'LIKE', '%'.$artiste.'%');
                        }
                        break;
                    default:
                        break;
                }
            }
        }
        return $songs->get()->toArray();
    }

    /**
     * Favourites a song.
     *
     * @param int $userId
     * @param int $songId
     * @return array
     */
    public function favouriteSong(int $userId, int $songId): array
    {
        $favourite = new Favourite();
        $favourite->user_id = $userId;
        $favourite->music_id = $songId;
        if ($favourite->save()) {
            return $favourite->toArray();
        }
    }
}
