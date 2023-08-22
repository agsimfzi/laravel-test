<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/* Auth */
Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');

Route::group(['middleware' => 'auth'], function () {

    /* Auth */
    Route::get('/refresh-token', 'AuthController@refresh');
    Route::post('/logout', 'AuthController@logout');

    /* Users */
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', 'UserController@index');
        Route::get('/{user}', 'UserController@show');
    });

    /* Products */
    Route::apiResource('products', 'ProductController');

});
