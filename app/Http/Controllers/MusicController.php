<?php

namespace App\Http\Controllers;

use App\Domains\Music as MusicDomain;
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

    private $musicTransformer;

    /**
     * MusicController constructor.
     *
     * @param Request     $request
     * @param MusicDomain $musicDomain
     */
    public function __construct(Request $request, MusicDomain $musicDomain, MusicTransformer $musicTransformer)
    {
        parent::__construct($request);
        $this->musicDomain = $musicDomain;
        $this->musicTransformer = $musicTransformer;
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
        $this->validate($this->request, MusicModel::$rules);

        $title = $this->request->get('title');
        $musicFile = $this->request->file('song');
        $artistes = explode(',', $this->request->get('artistes'));
        $albumId = $this->request->get('albumId');
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
            $albumId);

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
     *
     * @throws Exception
     *
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
     *
     * @throws Exception
     *
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
}
