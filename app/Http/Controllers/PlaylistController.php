<?php


namespace App\Http\Controllers;


use App\Domains\Playlist as PlaylistDomain;
use App\Playlist;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PlaylistController extends Controller
{
    /**
     * @var PlaylistDomain
     */
    private $playlistDomain;

    /**
     * PlaylistController constructor.
     *
     * @param Request $request
     * @param PlaylistDomain $playlistDomain
     */
    public function __construct(Request $request, PlaylistDomain $playlistDomain)
    {
        parent::__construct($request);
        $this->playlistDomain = $playlistDomain;
    }

    /**
     * Retrieve playlists.
     *
     * @return JsonResponse
     */
    public function getPlaylists(): JsonResponse
    {
        $searchParams = $this->get('title');
        $response = $this->playlistDomain->fetchPlaylists($searchParams);
        return response()->json([
            'message' => 'success',
            'data'    => $response,
        ]);
    }

    /**
     * Creates a new playlist.
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function create():JsonResponse
    {
        $this->validate($this->request, Playlist::$rules);
        $response = $this->playlistDomain->newPlaylist(
            $this->get('title'),
            Auth::user()->getAuthIdentifier()
        );
        return response()->json([
            'message' => 'success',
            'data'    => $response
        ]);
    }

    /**
     * Add playlist to user's playlist.
     *
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function sharePlaylist(int $id): JsonResponse
    {
        $userId = $this->get('userId');
        $this->playlistDomain->attachUser($userId, $id);

        return response()->json([
            'message'=> 'success'
        ]);
    }

    /**
     * Removes existing playlist.
     *
     * @param int $id
     * @throws Exception
     * @return JsonResponse
     */
    public function deletePlaylist(int $id): JsonResponse
    {
        $this->playlistDomain->deletePlaylist($id);
        return response()->json([
            'message' => 'success'
        ]);
    }
}