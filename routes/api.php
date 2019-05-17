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
    $router->post('/{id}/comment', 'MusicController@addComment');
    $router->post('/{id}/favourite', 'MusicController@addFavourite');
    $router->get('/', 'MusicController@getSongs');
});

$router->group([
    'middleware' => ['auth', 'role:Normal|Admin|Artiste'],
    'prefix'     => 'playlist',
], static function () use ($router) {
    $router->post('/', 'PlaylistController@create');
    $router->delete('/{id}', 'PlaylistController@deletePlaylist');
});

$router->group([
    'middleware' => ['auth', 'role:Admin|Artiste'],
    'prefix'     => 'genre',
], static function () use ($router) {
    $router->post('/', 'GenreController@addNewGenre');
});

$router->group([
    'middleware' => ['auth', 'role:Admin|Artiste|Normal'],
    'prefix'     => 'favourite',
], static function () use ($router) {
    $router->get('/', 'FavouriteController@getFavouriteSongs');
});
