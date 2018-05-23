<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/auth/register', 'AuthController@register');
$router->post('/auth/login', 'AuthController@login');
$router->get('/auth/refresh-user', 'AuthController@refreshUser');
$router->post('/auth/update-user', 'AuthController@updateUser');

resource($router, 'address', 'API\AddressController');
resource($router, 'application', 'API\ApplicationController');
resource($router, 'contract', 'API\ContractController');

function resource($router, $uri, $controller){
    $router->get($uri, [ 'uses' => $controller . '@index']);
    $router->post($uri, [ 'uses' => $controller . '@store']);
    $router->get($uri.'/{id}', [ 'uses' => $controller . '@show']);
    $router->put($uri.'/{id}', [ 'uses' => $controller . '@update']);
    $router->patch($uri.'/{id}', [ 'uses' => $controller . '@update']);
    $router->delete($uri.'/{id}', [ 'uses' => $controller . '@destroy']);
}
