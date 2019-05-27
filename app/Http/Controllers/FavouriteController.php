<?php

namespace App\Http\Controllers;

use App\Domains\Favourite as FavouriteDomain;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavouriteController extends Controller
{
    /**
     * @var FavouriteDomain
     */
    private $favouriteDomain;

    /**
     * FavouriteController constructor.
     *
     * @param Request         $request
     * @param FavouriteDomain $favouriteDomain
     */
    public function __construct(Request $request, FavouriteDomain $favouriteDomain)
    {
        parent::__construct($request);
        $this->favouriteDomain = $favouriteDomain;
    }

    /**
     * Fetch favourite songs.
     *
     * @throws Exception
     *
     * @return JsonResponse
     */
    public function getFavouriteSongs(): JsonResponse
    {
        $response = $this->favouriteDomain->getFavourites(
            Auth::user()->getAuthIdentifier()
        );

        return response()->json([
            'message' => 'success',
            'data'    => $response,
        ]);
    }
}
