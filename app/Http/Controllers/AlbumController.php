<?php

namespace App\Http\Controllers;

use App\Album as AlbumModel;
use App\Domains\Album;
use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
     * @param Album   $albumDomain
     */
    public function __construct(Request $request, Album $albumDomain)
    {
        parent::__construct($request);
        $this->albumDomain = $albumDomain;
    }

    /**
     * Retrieve albums.
     *
     * @return JsonResponse
     */
    public function fetchAlbums(): JsonResponse
    {
        $searchParams = [];
        if ($this->get('title')) {
            $searchParams['title'] = $this->get('title');
        }

        if ($this->get('artistes')) {
            $searchParams['artistes'] = $this->get('artistes');
        }

        if ($this->get('releaseDate')) {
            $searchParams['releaseDate'] = $this->get('releaseDate');
        }

        $response = $this->albumDomain->getAllAlbums($searchParams);

        return response()->json([
            'message' => 'success',
            'data'    => $response,
        ]);
    }

    /**
     * Add new album.
     *
     * @throws ValidationException
     *
     * @return JsonResponse
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
            'data'    => $response,
        ]);
    }

    /**
     * Update an existing album.
     *
     * @param int $id
     *
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws ValidationException
     *
     * @return JsonResponse
     */
    public function updateAlbum(int $id): JsonResponse
    {
        $this->validate($this->request, AlbumModel::$updateRules);
        $payload = $this->request->input();

        if (empty($payload)) {
            throw new BadRequestException('No update details provided');
        }

        $response = $this->albumDomain->updateAlbum($id, $payload);

        return response()->json([
            'message' => 'success',
            'data'    => $response,
        ]);

    }

    /**
     * Adds already existing songs to album.
     *
     * @param $id
     *
     * @throws NotFoundException
     *
     * @return JsonResponse
     */
    public function addSong($id): JsonResponse
    {
        $musicId = $this->get('musicId');
        $response = $this->albumDomain->addSong($id, $musicId);

        return response()->json([
            'message' => 'success',
            'data'    => $response,
        ]);
    }

    /**
     * Deletes album.
     *
     * @param int $id
     *
     * @throws NotFoundException
     *
     * @return JsonResponse
     */
    public function deleteAlbum(int $id): JsonResponse
    {
        $this->albumDomain->deleteAlbum($id);

        return response()->json([
            'message' => 'success',
            'data'    => 'Album deleted successfully.',
        ]);
    }
}
