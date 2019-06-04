<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Domains\Comment as CommentDomain;
use App\Domains\Music as MusicDomain;
use App\Domains\Playlist as PlaylistDomain;
use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Music as MusicModel;
use App\Services\GoogleCloudStorage\GoogleStorageAdapter;
use App\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use League\Flysystem\Config;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MusicController extends Controller
{
    /**
     * @var MusicDomain
     */
    private $musicDomain;

    /**
     * @var CommentDomain
     */
    private $commentDomain;

    /**
     * @var PlaylistDomain
     */
    private $playlistDomain;

    /**
     * @var GoogleStorageAdapter
     */
    private $googleStorageAdapter;

    /**
     * MusicController constructor.
     *
     * @param Request              $request
     * @param MusicDomain          $musicDomain
     * @param CommentDomain        $commentDomain
     * @param PlaylistDomain       $playlistDomain
     * @param GoogleStorageAdapter $googleStorageAdapter
     */
    public function __construct(
        Request $request,
        MusicDomain $musicDomain,
        CommentDomain $commentDomain,
        PlaylistDomain $playlistDomain,
        GoogleStorageAdapter $googleStorageAdapter
    ) {
        parent::__construct($request);
        $this->musicDomain = $musicDomain;
        $this->commentDomain = $commentDomain;
        $this->playlistDomain = $playlistDomain;
        $this->googleStorageAdapter = $googleStorageAdapter;
    }

    /**
     * Creates new music and stores it in audio file.
     *
     * @throws ValidationException
     *
     * @return JsonResponse
     */
    public function addNewSong(): JsonResponse
    {
        $config = new Config();
        $this->validate($this->request, MusicModel::$rules);

        $title = $this->request->get('title');
        $musicFile = $this->request->file('song');
        $artistes = $this->request->get('artistes');
        $albumId = $this->request->get('albumId');
        $genreId = $this->request->get('genreId');
        $originalName = $musicFile->getClientOriginalName();
        $extension = $musicFile->getClientOriginalExtension();
        $uniqueName = uniqid('fl_', false);

        $config->set('name', $uniqueName);
        $response = $this->googleStorageAdapter->write($musicFile->getPath(),
            file_get_contents($musicFile), $config);
        $location = getenv('GOOGLE_CLOUD_STORAGE_API_URI').'/'.$response['path'];

        $response = $this->musicDomain->newMusic(
            $title,
            $location,
            $originalName,
            $extension,
            $uniqueName,
            $artistes,
            $genreId,
            $albumId
        );

        if (!empty($response)) {
            return response()->json([
                'message' => 'success',
                'data'    => $response,
            ]);
        }

        return response()->json([
            'message' => 'Error',
            'data'    => 'An error occurred while trying to add a song.',
        ], 500);
    }

    /**
     * Updates a song's details.
     *
     * @param $id
     *
     * @throws Exception
     *
     * @return JsonResponse
     */
    public function updateSong($id): JsonResponse
    {
        $payload = [];
        if (!empty($this->request->input('title'))) {
            $payload['title'] = $this->request->input('title');
        }

        if (!empty($this->request->input('artistes'))) {
            $payload['artistes'] = $this->request->input('artistes');
        }

        if (!empty($this->request->input('albumId'))) {
            $payload['album_id'] = $this->request->input('albumId');
        }

        if (!empty($this->request->input('genreId'))) {
            $payload['genreId'] = $this->request->input('genreId');
        }

        if (!empty($this->request->file('song'))) {
            $previousName = MusicModel::find($id);
            if (empty($previousName)) {
                throw new NotFoundException('Song not found.');
            }
            $previousName = $previousName->get('uniqueName');
            $config = new Config();
            $config->set('name', $previousName);
            $musicFile = $this->request->file('song');
            $response = $this->googleStorageAdapter->write($musicFile->getPath(),
                file_get_contents($musicFile), $config);
            $location = getenv('GOOGLE_CLOUD_STORAGE_API_URI').'/'.$response['path'];
            $originalName = $musicFile->getClientOriginalName();
            $uniqueName = uniqid('audio_', true);
            $extension = $musicFile->getClientOriginalExtension();

            $payload['location'] = $location;
            $payload['extension'] = $extension;
            $payload['originalName'] = $originalName;
            $payload['uniqueName'] = $uniqueName;
        }

        $response = $this->musicDomain->updateSong($id, $payload);
        if (!empty($response)) {
            return response()->json([
                'message' => 'success',
                'data'    => $response,
            ], 202);
        }
    }

    /**
     * Deletes a song given the song id.
     *
     * @param $id
     *
     * @throws Exception
     *
     * @return JsonResponse
     */
    public function deleteSong(int $id): JsonResponse
    {
        $path = MusicModel::where('id', $id)->first();
        if (empty($path)) {
            throw new NotFoundException('Song not found');
        }
        $path = $path->toArray()['uniqueName'];
        if ($this->musicDomain->removeSong($id)) {
            $this->googleStorageAdapter->delete($path);

            return response()->json([
                'message' => 'Success',
                'data'    => 'song deleted successfully.',
            ]);
        }
    }

    /**
     * Add recommended song to the user's list.
     *
     * @param int $id
     *
     * @throws BadRequestException
     * @throws NotFoundException
     *
     * @return JsonResponse
     */
    public function recommendSong(int $id): JsonResponse
    {
        $userId = $this->get('userId');
        $recommendedBy = Auth::user()->getAuthIdentifier();
        $this->musicDomain->attachMusic($id, $userId, $recommendedBy);

        return response()->json([
              'message' => 'success',
          ]);
    }

    /**
     * Fetch all recommended songs.
     *
     * @return JsonResponse
     */
    public function fetchRecommendedSongs(): JsonResponse
    {
        $recommendedSongs = Auth::user()->recommend()->get()->toArray();
        $response = [];
        foreach ($recommendedSongs as $recommendedSong) {
            $response[] = [
                'id'            => $recommendedSong['id'],
                'title'         => $recommendedSong['title'],
                'originalName'  => $recommendedSong['originalName'],
                'extension'     => $recommendedSong['extension'],
                'location'      => $recommendedSong['location'],
                'uniqueName'    => $recommendedSong['uniqueName'],
                'artistes'      => $recommendedSong['artistes'],
                'album_id'      => $recommendedSong['album_id'],
                'genreId'       => $recommendedSong['genreId'],
                'created_at'    => $recommendedSong['created_at'],
                'updated_at'    => $recommendedSong['updated_at'],
                'recommendedBy' => User::find($recommendedSong['pivot']['recommended_by'])->toArray(),
            ];
        }

        return response()->json([
            'message' => 'success',
            'data'    => $response,
        ]);
    }

    /**
     * Adds a new comment and|or rating.
     *
     * @param $id
     *
     * @throws BadRequestException
     * @throws ValidationException
     *
     * @return JsonResponse
     */
    public function addComment($id): JsonResponse
    {
        $this->validate($this->request, Comment::$rules);
        if (empty($this->request->input())) {
            throw new BadRequestException('No ratings or comment provided');
        }
        $userId = Auth::user()->getAuthIdentifier();
        $response = $this->commentDomain->newComment($userId, $id, $this->get('comment'), $this->get('rating'));

        if (!empty($response)) {
            return response()->json([
                'message' => 'success',
                'data'    => $response,
            ]);
        }
    }

    /**
     * Removes an existing comment.
     *
     * @param $id
     * @param $commentId
     *
     * @throws NotFoundException
     *
     * @return JsonResponse
     */
    public function deleteComment($id, $commentId): JsonResponse
    {
        $this->commentDomain->removeComment($commentId);

        return response()->json([
            'message' => 'success',
        ]);
    }

    /**
     * Retrieves songs.
     *
     * @return JsonResponse
     */
    public function getSongs(): JsonResponse
    {
        $filters = $this->request->input();
        $response = $this->musicDomain->getSongs($filters);

        return response()->json([
            'message' => 'success',
            'data'    => $response,
        ]);
    }

    /**
     * Add song to favourite category.
     *
     * @param int $id
     *
     * @throws Exception
     *
     * @return JsonResponse
     */
    public function addFavourite(int $id): JsonResponse
    {
        $response = $this->musicDomain->favouriteSong(
            Auth::user()->getAuthIdentifier(),
            $id
        );

        return response()->json([
            'message' => 'success',
            'data'    => $response,
        ]);
    }

    /**
     * Add a song to the playlist.
     *
     * @param int $id
     *
     * @throws Exception
     *
     * @return JsonResponse
     */
    public function addToPlaylist(int $id): JsonResponse
    {
        $playlistId = $this->get('playlistId');
        $this->playlistDomain->attachSong($playlistId, $id);

        return response()->json([
            'message'=> 'success',
        ]);
    }

    /**
     * Downloads a file.
     *
     * @param $id
     *
     * @return BinaryFileResponse
     */
    public function downloadSong($id): BinaryFileResponse
    {
        $filters = ['id' => $id];
        $response = $this->musicDomain->getSongs($filters)[0];
        $file = $this->googleStorageAdapter->read('');
        file_put_contents('tempFile', $file);

        $headers = [
            'Content-Type: application/'.$response['extension'],
        ];

        return response()->download('tempFile', $response['originalName'], $headers);
    }
}
