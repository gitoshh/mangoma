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
    $router->post('refresh', 'AuthController@refresh');
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
