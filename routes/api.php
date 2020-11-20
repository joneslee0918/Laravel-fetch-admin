<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the 'api' middleware group. Enjoy building your API!
|
*/
Route::post( 'login', 'App\Http\Controllers\API\UserController@login' );
Route::post( 'signup', 'App\Http\Controllers\API\UserController@signup' );

Route::group( ['middleware' => 'auth:api'], function () {

    Route::get( 'home', 'App\Http\Controllers\API\HomeController@home' );
    Route::post( 'home/filter_category', 'App\Http\Controllers\API\HomeController@filter_category' );
    Route::post( 'home/filter', 'App\Http\Controllers\API\HomeController@filter' );
    Route::post( 'home/search', 'App\Http\Controllers\API\HomeController@search' );

    //ads
    Route::post( 'ads/ad_detail', 'App\Http\Controllers\API\AdsController@adDetail' );
    Route::post( 'ads/ad_favourite', 'App\Http\Controllers\API\AdsController@adFavourite' );
    Route::get( 'ads/sell', 'App\Http\Controllers\API\AdsController@sell' );

    //profile
    Route::post( 'profile', 'App\Http\Controllers\API\UserController@profile' );
});
