<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix'=>'auth'], function () {
    Route::post('register', 'App\Http\Controllers\AuthController@register');
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::group(['prefix'=>'{provider}'], function () {
        Route::get('/', function ($provider) {
            return Socialite::driver($provider)->stateless()->redirect();
        });
        Route::get('/callback', 'App\Http\Controllers\AuthController@socialLogin');
    });
    Route::post('/reset-password', 'App\Http\Controllers\AuthController@passwordReset');
    Route::post('/change-password', 'App\Http\Controllers\AuthController@passwordChange');
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['prefix' => 'users'], function () {
        Route::get('/{id}', 'App\Http\Controllers\UserController@get_user_by_id');
        Route::post('/{id}', 'App\Http\Controllers\UserController@change');
        Route::get('/{id}/products', 'App\Http\Controllers\ProductController@get_products_by_user_id');

    });
    Route::group(['prefix' => 'products'], function () {
        Route::post('', 'App\Http\Controllers\ProductController@create');
        Route::patch('/{id}', 'App\Http\Controllers\ProductController@change');
        Route::delete('/{id}', 'App\Http\Controllers\ProductController@delete');
    });
    Route::group(['prefix' => 'orders'], function () {
        Route::get('', 'App\Http\Controllers\OrderController@get_all');
        Route::patch('{id}', 'App\Http\Controllers\OrderController@change_status');
    });
    Route::get('/admin_categories','App\Http\Controllers\CategoryController@getAll');
});
Route::group(['prefix' => 'categories'], function () {
    Route::get('','App\Http\Controllers\CategoryController@get');
    Route::post('','App\Http\Controllers\CategoryController@create');
    Route::delete('{id}', 'App\Http\Controllers\CategoryController@delete');
});
Route::group(['prefix' => 'products'], function () {
    Route::get('/{id}', 'App\Http\Controllers\ProductController@get_by_id');
    Route::get('','App\Http\Controllers\ProductController@getAll');
});
Route::post('/orders', 'App\Http\Controllers\OrderController@create');
