<?php

use Illuminate\Http\Request;

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

Route::group(['middleware' => 'json.response'], function () {

    Route::get('users', 'Auth\AuthController@users');

    Route::post('login', 'Auth\AuthController@login')->name('login');

	Route::get('product/shop-view', 'ProductController@shopView');

    Route::get('brand', 'BrandController@index');

    Route::get('category', 'CategoryController@index');

    Route::post('order', 'OrderController@store');

    Route::group(['middleware' => 'auth:api'], function ($router) {

        Route::post('/logout', 'Auth\AuthController@logout');
        Route::get('user', 'Auth\AuthController@user');

        Route::post('brand/store', 'BrandController@store');


    	Route::post('category/store', 'CategoryController@store');

        Route::get('product', 'ProductController@index');
    	Route::post('product/store', 'ProductController@store');
        Route::put('product/update/{id}', 'ProductController@update');
        Route::delete('product/delete/{id}', 'ProductController@destroy');

    	
    });
});
