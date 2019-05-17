<?php


namespace App\Http\Controllers;

use App\Domains\Genre as GenreDomain;
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
     * @param Request $request
     * @param GenreDomain $genreDomain
     */
    public function __construct(Request $request, GenreDomain $genreDomain)
    {
        parent::__construct($request);
        $this->genreDomain = $genreDomain;
    }

    /**
     * @return JsonResponse
     * @throws ValidationException
     */
    public function addNewGenre(): JsonResponse
    {
        $this->validate($this->request, Genre::$rules);
        $response = $this->genreDomain->newGenre($this->get('name'));
        return response()->json([
            'message' => 'success',
            'data'    => $response
        ]);
    }
}