<?php

namespace App\Domains;

use App\Favourite as FavouriteModel;
use Exception;

class Favourite
{
    /**
     * Retrieve all favourite songs.
     *
     * @param int $userId
     *
     * @throws Exception
     *
     * @return array
     */
    public function getFavourites(int $userId): array
    {
        return FavouriteModel::with('music')
            ->where('user_id', $userId)
            ->get()
            ->toArray();
    }
}
