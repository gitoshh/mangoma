<?php

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/users', 'UsersController@new');
$router->post('/auth/login', 'AuthController@authenticate');

$router->group([
    'middleware' => 'auth',
    'prefix'     => 'auth',
], static function () use ($router) {
    $router->post('logout', 'AuthController@logout');
});

$router->group([
    'middleware' => ['auth', 'role:Admin'],
    'prefix'     => 'admin',
], static function () use ($router) {
    $router->post('permission', 'EntrustController@newPermission');
    $router->post('role', 'EntrustController@newRole');
    $router->post('attach/role', 'EntrustController@addRole');
    $router->post('attach/permission', 'EntrustController@addPermissions');
});

$router->group([
    'middleware' => ['auth', 'role:Artiste|Admin'],
    'prefix'     => 'album',
], static function () use ($router) {
    $router->post('/', 'AlbumController@createNewAlbum');
    $router->get('/', 'AlbumController@fetchAlbums');
    $router->post('/{id}/song', 'AlbumController@addSong');
    $router->put('/{id}', 'AlbumController@updateAlbum');
    $router->delete('/{id}', 'AlbumController@deleteAlbum');
});

$router->group([
    'middleware' => ['auth', 'role:Artiste|Admin'],
    'prefix'     => 'music',
], static function () use ($router) {
    $router->post('/', 'MusicController@addNewSong');
    $router->put('/{id}', 'MusicController@updateSong');
    $router->delete('/{id}', 'MusicController@deleteSong');
});

$router->group([
    'middleware' => ['auth', 'role:Artiste|Admin|Normal'],
    'prefix'     => 'music',
], static function () use ($router) {
    $router->post('/{id}/recommend', 'MusicController@recommendSong');
    $router->get('/recommended', 'MusicController@fetchRecommendedSongs');
    $router->post('/{id}/comment', 'MusicController@addComment');
    $router->delete('/{id}/comment/{commentId}', 'MusicController@deleteComment');
    $router->post('/{id}/favourite', 'MusicController@addFavourite');
    $router->post('/{id}/playlist', 'MusicController@addToPlaylist');
    $router->get('/', 'MusicController@getSongs');
    $router->get('/{id}/download', 'MusicController@downloadSong');
});

$router->group([
    'middleware' => ['auth', 'role:Premium|Admin|Artiste'],
    'prefix'     => 'playlist',
], static function () use ($router) {
    $router->post('/', 'PlaylistController@create');
    $router->get('/', 'PlaylistController@getPlaylists');
    $router->post('/{id}/share', 'PlaylistController@sharePlaylist');
    $router->delete('/{id}', 'PlaylistController@deletePlaylist');
});

$router->group([
    'middleware' => ['auth', 'role:Admin|Artiste'],
    'prefix'     => 'genre',
], static function () use ($router) {
    $router->post('/', 'GenreController@addNewGenre');
    $router->get('/', 'GenreController@getGenre');
    $router->delete('/{id}', 'GenreController@deleteGenre');
});

$router->group([
    'middleware' => ['auth', 'role:Admin|Artiste|Premium'],
    'prefix'     => 'favourite',
], static function () use ($router) {
    $router->get('/', 'FavouriteController@getFavouriteSongs');
});

$router->group([
    'middleware' => ['auth'],
    'prefix'     => 'stripe',
], static function () use ($router) {
    $router->post('/token', 'PaymentController@createToken');
    $router->post('/subscribe', 'PaymentController@newSubscription');
    $router->post('/cancel', 'PaymentController@cancelSubscription');
    $router->get('/invoices', 'PaymentController@viewInvoices');
    $router->get('/invoices/{id}', 'PaymentController@downloadInvoice');
});
