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

    //change password
    Route::post( 'changepassword', 'App\Http\Controllers\API\UserController@changePassword' );

    //home
    Route::get( 'home', 'App\Http\Controllers\API\HomeController@home' );
    Route::post( 'home/filter_category', 'App\Http\Controllers\API\HomeController@filter_category' );
    Route::post( 'home/filter', 'App\Http\Controllers\API\HomeController@filter' );

    //ads
    Route::post( 'ads', 'App\Http\Controllers\API\AdsController@adDetail' );
    Route::post( 'ads/ad_favourite', 'App\Http\Controllers\API\AdsController@adFavourite' );
    Route::get( 'ads/sell', 'App\Http\Controllers\API\AdsController@sell' );
    Route::post( 'ads/create', 'App\Http\Controllers\API\AdsController@create' );
    Route::post( 'ads/edit', 'App\Http\Controllers\API\AdsController@edit' );

    //profile
    Route::post( 'profile', 'App\Http\Controllers\API\UserController@profile' );
    Route::post( 'profile/edit', 'App\Http\Controllers\API\UserController@edit' );

    //inbox
    Route::get( 'inbox', 'App\Http\Controllers\API\InboxController@inbox' );

    //chat
    Route::post( 'chat', 'App\Http\Controllers\API\ChatController@chat' );
    Route::post( 'chat/post', 'App\Http\Controllers\API\ChatController@postMessage' );

    //notification
    Route::get( 'notification', 'App\Http\Controllers\API\NotificationController@notification' );
    Route::post( 'notification/read', 'App\Http\Controllers\API\NotificationController@read' );
});
