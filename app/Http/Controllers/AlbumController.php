<?php

namespace App\Http\Controllers;

use App\Domains\Album;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Album as AlbumModel;
use Illuminate\Validation\ValidationException;

class AlbumController extends Controller
{
    /**
     * @var Album
     */
    private $albumDomain;

    /**
     * AlbumController constructor.
     *
     * @param Request $request
     * @param Album $albumDomain
     */
    public function __construct(Request $request, Album $albumDomain)
    {
        parent::__construct($request);
        $this->albumDomain = $albumDomain;
    }

    /**
     * Add new album.
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function createNewAlbum(): JsonResponse
    {
        $this->validate($this->request, AlbumModel::$rules);
        $payload = $this->request->only('title', 'releaseDate', 'artistes');
        $response = $this->albumDomain->newAlbum(
            $payload['title'],
            $payload['releaseDate'],
            $payload['artistes']
        );

        return response()->json([
            'message' => 'success',
            'data'    => $response
        ]);
    }

    public function updateAlbum()
    {
    }

    /**
     * Adds already existing songs to album.
     *
     * @param $id
     * @return JsonResponse
     */
    public function addSong($id): JsonResponse
    {
        $musicId = $this->request->get('musicId');
        $response = $this->albumDomain->addSong($id, $musicId);
        return response()->json([
            'message' => 'success',
            'data'    => $response
        ]);
    }

    /**
     * Deletes album.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function deleteAlbum(int $id): JsonResponse
    {
        $this->albumDomain->deleteAlbum($id);
        return response()->json([
            'message' => 'success',
            'data'    => 'Album deleted successfully.'
        ]);

    }
}