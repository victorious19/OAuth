<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['prefix' => 'oauth'], function() {
    Route::get('redirect', 'App\Http\Controllers\OAuthController@redirect');
    Route::get('callback', 'App\Http\Controllers\OAuthController@callback');
    Route::get('refresh', 'App\Http\Controllers\OAuthController@refresh');
});
Route::get('/{any}', function () {
    return view('index');
})->where('any', '.*');
