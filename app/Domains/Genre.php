<?php

namespace App\Domains;

use App\Genre as GenreModel;

class Genre
{
    /**
     * Creates new genre.
     *
     * @param string $name
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
}