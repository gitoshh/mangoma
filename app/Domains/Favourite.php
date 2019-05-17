<?php


namespace App\Domains;

use App\Favourite as FavouriteModel;

class Favourite
{
    /**
     * Retrieve all favourite songs
     * @param int $userId
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