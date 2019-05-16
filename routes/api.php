<?php

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/users', 'UsersController@new');
$router->post('/auth/login', 'AuthController@authenticate');

$router->group([
    'middleware' => 'auth',
    'prefix'     => 'auth',
], function () use ($router) {
    $router->post('logout', 'AuthController@logout');
});

$router->group([
    'middleware' => ['auth', 'role:Admin'],
    'prefix'     => 'admin',
], function () use ($router) {
    $router->post('permission', 'EntrustController@newPermission');
    $router->post('role', 'EntrustController@newRole');
    $router->post('attach/role', 'EntrustController@addRole');
    $router->post('attach/permission', 'EntrustController@addPermissions');
});

$router->group([
    'middleware' => ['auth', 'role:Artiste|Admin'],
    'prefix'     => 'music',
], function () use ($router) {
    $router->post('/', 'MusicController@addNewSong');
    $router->put('/{id}', 'MusicController@updateSong');
    $router->delete('/{id}', 'MusicController@deleteSong');
});

$router->group([
    'middleware' => ['auth', 'role:Normal|Admin|Artiste'],
    'prefix'     => 'playlist',
], function () use ($router) {
    $router->post('/', 'PlaylistController@create');
//    $router->put('/{id}', 'MusicController@updateSong');
    $router->delete('/{id}', 'PlaylistController@deletePlaylist');
});
