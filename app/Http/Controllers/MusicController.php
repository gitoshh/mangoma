<?php

namespace App\Http\Controllers;

use App\Domains\Music as MusicDomain;
use App\Music as MusicModel;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MusicController extends Controller
{
    /**
     * @var MusicDomain
     */
    private $musicDomain;

    /**
     * MusicController constructor.
     *
     * @param Request     $request
     * @param MusicDomain $musicDomain
     */
    public function __construct(Request $request, MusicDomain $musicDomain)
    {
        parent::__construct($request);
        $this->musicDomain = $musicDomain;
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
        $originalName = $musicFile->getClientOriginalName();
        $extension = $musicFile->getClientOriginalExtension();

        $location = public_path('audio/'.$originalName);
        $uniqueName = uniqid('audio_', true);
        $location = edit_uploaded_file_location($location, $uniqueName).'.'.$extension;
        $musicFile->move($location, $originalName);

        $response = $this->musicDomain->newMusic($title, $location, $originalName, $extension, $uniqueName);

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
            $payload = [
                    'title' => $this->request->input('title'),
                ];
        }

        if (!empty($this->request->file('song'))) {
            $musicFile = $this->request->file('song');
            $originalName = $musicFile->getClientOriginalName();
            $extension = $musicFile->getClientOriginalExtension();
            $location = public_path('audio/'.$originalName);

            $payload['location'] = $location;
            $payload['extension'] = $extension;
            $payload['originalName'] = $originalName;
        }
        $response = $this->musicDomain->updateSong($id, $payload);
        if (!empty($response)) {
            return response()->json([
                'message' => 'success',
                'data'    => $response,
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
}
