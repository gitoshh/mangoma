<?php

namespace App\Domains;

use App\Exceptions\NotFoundException;
use App\Genre as GenreModel;

class Genre
{
    /**
     * Creates new genre.
     *
     * @param string $name
     *
     * @return array
     */
    public function newGenre(string $name): array
    {
        $genre = new GenreModel();
        $genre->name = $name;
        if ($genre->save()) {
            return $genre->toArray();
        }
    }

    /**
     * Retrieves genres.
     *
     * @param string|null $searchParams
     *
     * @return array
     */
    public function FetchCategories(?string $searchParams = null): array
    {
        $response = [];
        if ($searchParams) {
            $genres = GenreModel::where('name', 'LIKE', $searchParams)->get();
        } else {
            $genres = GenreModel::all();
        }

        foreach ($genres as $item) {
            $response[] = [
                'name'  => $item->name,
                'songs' => $item->music()->get()->toArray(),
            ];
        }

        return $response;
    }

    /**
     * Remove genre by id.
     *
     * @param int $id
     *
     * @throws NotFoundException
     */
    public function deleteGenre(int $id): void
    {
        $genre = GenreModel::find($id);
        if (empty($genre)) {
            throw new NotFoundException('Genre not found!', 'SCHNCF');
        }
        $genre->delete();
    }
}
