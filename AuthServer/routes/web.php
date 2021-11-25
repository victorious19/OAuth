<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

use Laravel\Socialite\Facades\Socialite;

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->group(['prefix' => 'api'], function() use($router) {
    $router->get('answer', 'AuthController@answer');
    $router->group(['prefix'=>'auth'], function () use($router){
        $router->post('register', 'AuthController@register');
        $router->post('login', 'AuthController@login');
        $router->group(['prefix'=>'{provider}'], function () use($router){
            $router->get('/', function ($provider) {
                return Socialite::driver($provider)->stateless()->redirect();
            });
            $router->get('/callback', 'AuthController@socialLogin');
        });
        $router->post('/reset-password', 'AuthController@passwordReset');
        $router->post('/change-password', 'AuthController@passwordChange');
    });
    $router->group(['middleware' => 'auth'], function() use($router) {
        $router->get('/oauth/authorize', 'AuthController@oauth');
    });
});
