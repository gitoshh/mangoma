<?php

namespace App\Domains;

use App\Exceptions\NotFoundException;
use App\Playlist as PlaylistModel;
use App\User as UserModel;
use Exception;

class Playlist
{
    /**
     * Adds a new playlist.
     *
     * @param string $title
     * @param int    $creatorId
     *
     * @return array
     */
    public function newPlaylist(string $title, int $creatorId): array
    {
        $playlist = new PlaylistModel();
        $playlist->title = $title;
        $playlist->creator = $creatorId;

        if ($playlist->save()) {
            return $playlist->toArray();
        }

        return [];
    }

    /**
     * Removes playlist given id.
     *
     * @param int $id
     *
     * @throws Exception
     */
    public function deletePlaylist(int $id): void
    {
        $playlist = PlaylistModel::find($id);
        if ($playlist === null) {
            throw new Exception('Playlist not found.');
        }
        $playlist->delete();
    }

    /**
     * Retrieve  playlists.
     *
     * @param string|null $searchParams
     *
     * @return array
     */
    public function fetchPlaylists(?string $searchParams = null): array
    {
        if ($searchParams) {
            $playlists = PlaylistModel::where('title', 'LIKE', $searchParams)->get();
        } else {
            $playlists = PlaylistModel::all();
        }

        $response = [];

        foreach ($playlists as $item) {
            $response[] = [
                'id'      => $item->id,
                'name'    => $item->title,
                'creator' => $item->creator,
                'music'   => $item->music()->get()->toArray(),
            ];
        }

        return $response;
    }

    /**
     * Add relationship between playlist and song.
     *
     * @param int $playlistId
     * @param int $songId
     *
     * @throws Exception
     */
    public function attachSong(int $playlistId, int $songId): void
    {
        $playlist = PlaylistModel::find($playlistId);
        if ($playlist) {
            $playlist->music()->attach($songId);
        } else {
            throw new NotFoundException('Playlist not found');
        }
    }

    /**
     * Add relationship between user and playlist.
     *
     * @param int $userId
     * @param int $playlistId
     *
     * @throws Exception
     */
    public function attachUser(int $userId, int $playlistId): void
    {
        $user = UserModel::find($userId);
        if ($user) {
            $user->playlist()->attach($playlistId);
        } else {
            throw new NotFoundException('User not found');
        }
    }
}
