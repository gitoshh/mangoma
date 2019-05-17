<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Domains\Comment as CommentDomain;
use App\Domains\Music as MusicDomain;
use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Music as MusicModel;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Http\Transformers\MusicTransformer;

class MusicController extends Controller
{
    /**
     * @var MusicDomain
     */
    private $musicDomain;

    /**
     * @var MusicTransformer
     */
    private $musicTransformer;

    /**
     * @var CommentDomain
     */
    private $commentDomain;

    /**
     * MusicController constructor.
     *
     * @param Request $request
     * @param MusicDomain $musicDomain
     * @param MusicTransformer $musicTransformer
     * @param CommentDomain $commentDomain
     */
    public function __construct(
        Request $request,
        MusicDomain $musicDomain,
        MusicTransformer $musicTransformer,
        CommentDomain $commentDomain
    ) {
        parent::__construct($request);
        $this->musicDomain = $musicDomain;
        $this->musicTransformer = $musicTransformer;
        $this->commentDomain = $commentDomain;
    }

    /**
     * Creates new music and stores it in audio file.
     *
     * @throws ValidationException
     * @return JsonResponse
     */
    public function addNewSong(): JsonResponse
    {
        $this->validate($this->request, MusicModel::$rules);

        $title = $this->request->get('title');
        $musicFile = $this->request->file('song');
        $artistes = $this->request->get('artistes');
        $albumId = $this->request->get('albumId');
        $genreId = $this->request->get('genreId');
        $originalName = $musicFile->getClientOriginalName();
        $extension = $musicFile->getClientOriginalExtension();

        $location = public_path('audio/');
        $uniqueName = uniqid('audio_', true);
        $uniqueNameExtension = $uniqueName.'.'.$extension;
        $musicFile->move($location, $uniqueNameExtension);
        $location = $location.'/'.$uniqueNameExtension;

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
                'data'    => $this->musicTransformer->transformSong($response),
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
     * @throws Exception
     * @return JsonResponse
     */
    public function updateSong($id): JsonResponse
    {
        $payload = [];
        if (!empty($this->request->input('title'))) {
            $payload = [
                    'title' => $this->request->input('title'),
                ];
        }

        if (!empty($this->request->input('artistes'))) {
            $payload = [
                'artistes' => $this->request->input('artistes'),
            ];
        }

        if (!empty($this->request->input('albumId'))) {
            $payload = [
                'albumId' => $this->request->input('albumId'),
            ];
        }

        if (!empty($this->request->file('song'))) {
            $musicFile = $this->request->file('song');
            $location = public_path('audio/');
            $originalName = $musicFile->getClientOriginalName();
            $uniqueName = uniqid('audio_', true);
            $extension = $musicFile->getClientOriginalExtension();
            $uniqueNameExtension = $uniqueName.'.'.$extension;
            $musicFile->move($location, $uniqueNameExtension);
            $location = $location.'/'.$uniqueNameExtension;

            $payload['location'] = $location;
            $payload['extension'] = $extension;
            $payload['originalName'] = $originalName;
            $payload['uniqueName'] = $uniqueName;
        }
        $response = $this->musicDomain->updateSong($id, $payload);
        if (!empty($response)) {
            return response()->json([
                'message' => 'success',
                'data'    => $this->musicTransformer->transformSong($response),
            ], 202);
        }

        return response()->json([
            'message' => 'Error',
            'data'    => 'An error occurred while trying to update a song.',
        ], 500);
    }

    /**
     * Deletes a song given the song id.
     *
     * @param $id
     * @throws Exception
     * @return JsonResponse
     */
    public function deleteSong(int $id): JsonResponse
    {
        if ($this->musicDomain->removeSong($id)) {
            return response()->json([
                'message' => 'Success',
                'data'    => 'song deleted successfully.',
            ]);
        }

        return response()->json([
            'message' => 'Error',
            'data'    => 'An error occurred while trying to delete a song.',
        ], 500);
    }

    /**
     * Add recommended song to the user's list.
     *
     * @param int $id
     * @return JsonResponse
     * @throws NotFoundException
     */
    public function recommendSong(int $id): JsonResponse
    {
        $userId = $this->get('userId');
        $recommendedBy = Auth::user()->getAuthIdentifier();
        $this->musicDomain->attachMusic($id, $userId, $recommendedBy);
        return response()->json([
              'message' => 'success'
          ]);
    }

    /**
     * Adds a new comment and|or rating.
     *
     * @param $id
     * @throws BadRequestException
     * @throws ValidationException
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
                'data'    => $response
            ]);
        }
        return response()->json([
            'message' => 'error'
        ], 500);
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
            'data'    => $response
        ]);
    }

    /**
     * Add song to favourite category.
     *
     * @param int $id
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
            'data'    => $response
        ]);
    }
}
