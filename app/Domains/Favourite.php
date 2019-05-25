<?php


namespace App\Domains;

use App\Favourite as FavouriteModel;
use Exception;
use Illuminate\Database\QueryException;

class Favourite
{
    /**
     * Retrieve all favourite songs
     * @param int $userId
     * @return array
     * @throws Exception
     */
    public function getFavourites(int $userId): array
    {
        return FavouriteModel::with('music')
            ->where('user_id', $userId)
            ->get()
            ->toArray();
    }
}