<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//****************************************************************
//************************* GraphQL ******************************
//****************************************************************

/** @var \Illuminate\Contracts\Routing\Registrar $router */
$router = app('router');

if ($route_config = config('lighthouse.route')) {

    $actions = [
        'as' => $route_config['name'] ?? 'lighthouse.graphql',
        'uses' => \Nuwave\Lighthouse\Support\Http\Controllers\GraphQLController::class,
    ];

    if (isset($route_config['middleware'])) {
        $actions['middleware'] = $route_config['middleware'];
    }

    if (isset($route_config['prefix'])) {
        $actions['prefix'] = $route_config['prefix'];
    }

    if (isset($route_config['domain'])) {
        $actions['domain'] = $route_config['domain'];
    }

    $router->addRoute(
        ['GET', 'POST'],
        $route_config['uri'] ?? 'graphql',
        $actions
    );
}