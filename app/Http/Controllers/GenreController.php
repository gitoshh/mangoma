<?php

namespace App\Http\Controllers;

use App\Domains\Genre as GenreDomain;
use App\Exceptions\NotFoundException;
use App\Genre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GenreController extends Controller
{
    /**
     * @var GenreDomain
     */
    private $genreDomain;

    /**
     * GenreController constructor.
     *
     * @param Request     $request
     * @param GenreDomain $genreDomain
     */
    public function __construct(
        Request $request,
        GenreDomain $genreDomain
    ) {
        parent::__construct($request);
        $this->genreDomain = $genreDomain;
    }

    /**
     * Create a new genre.
     *
     * @throws ValidationException
     *
     * @return JsonResponse
     */
    public function addNewGenre(): JsonResponse
    {
        $this->validate($this->request, Genre::$rules);
        $response = $this->genreDomain->newGenre($this->get('name'));

        return response()->json([
            'message' => 'success',
            'data'    => $response,
        ]);
    }

    /**
     * Fetch genres|a genre and its music.
     *
     * @return JsonResponse
     */
    public function getGenre(): JsonResponse
    {
        $searchParams = $this->get('name');
        $response = $this->genreDomain->FetchCategories($searchParams);

        return response()->json([
            'message' => 'success',
            'data'    => $response,
        ]);
    }

    /**
     * Removes a genre by id.
     *
     * @param $id
     *
     * @throws NotFoundException
     *
     * @return JsonResponse
     */
    public function deleteGenre($id): JsonResponse
    {
        $this->genreDomain->deleteGenre($id);

        return response()->json([
            'message' => 'success',
        ]);
    }
}
