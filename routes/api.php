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
Route::post( 'login', 'API\UserController@login' );
Route::post( 'signup', 'API\UserController@signup' );
Route::post( 'forgotpassword', 'API\UserController@forgotpassword' );
Route::get( 'check', 'API\UserController@check' );

Route::group( ['middleware' => 'auth:api'], function () {

    //ACCOUNT STATUS
    Route::post( 'accountStatus', 'API\UserController@accountStatus' );

    //CHANGE PASSWORD
    Route::post( 'changepassword', 'API\UserController@changePassword' );

    //HOME
    Route::get( 'home', 'API\HomeController@home' );
    Route::post( 'home/filter', 'API\HomeController@filter' );

    //DASHBOARD
    Route::get( 'dashboard', 'API\HomeController@dashboard' );
    
    //FILTER
    Route::get( 'filter', 'API\FilterController@index' );
    Route::post( 'filter/get', 'API\FilterController@getData' );

    //ADS
    Route::post( 'ads', 'API\AdsController@adDetail' );
    Route::post( 'ads/ad_favourite', 'API\AdsController@adFavourite' );
    Route::get( 'ads/sell', 'API\AdsController@sell' );
    Route::post( 'ads/image/delete', 'API\AdsController@deleteImage' );
    Route::post( 'ads/create', 'API\AdsController@create' );
    Route::post( 'ads/edit', 'API\AdsController@edit' );
    Route::post( 'ads/delete', 'API\AdsController@delete' );
    Route::post( 'ads/location/update', 'API\AdsController@locationUpdate' );
    
    //MYADS
    Route::get( 'ads/activeAds', 'API\AdsController@activeAds' );
    Route::get( 'ads/closedAds', 'API\AdsController@closedAds' );
    Route::get( 'ads/favouriteAds', 'API\AdsController@favouriteAds' );
    Route::post( 'ads/favouriteAds/search', 'API\AdsController@searchFavAds' );

    //PROFILE
    Route::post( 'profile', 'API\UserController@profile' );
    Route::post( 'profile/edit', 'API\UserController@edit' );
    Route::post( 'profile/setting', 'API\UserController@setUserMeta' );
    Route::post( 'profile/token', 'API\UserController@setDeviceToken' );
    Route::post( 'profile/follow', 'API\UserController@followUser' );

    //INBOX
    Route::get( 'inbox', 'API\InboxController@inbox' );
    Route::post( 'inbox/block', 'API\InboxController@blockUser' );
    Route::get( 'inbox/blocklist', 'API\InboxController@getBlockList' );
    Route::post( 'inbox/unblock', 'API\InboxController@unblockUser' );

    //CHAT
    Route::post( 'chat', 'API\ChatController@chat' );
    Route::post( 'chat/post', 'API\ChatController@postMessage' );
    Route::post( 'chat/read', 'API\ChatController@readMessage' );

    //NOTIFICATION
    Route::get( 'notification', 'API\NotificationController@notification' );
    Route::post( 'notification/read', 'API\NotificationController@read' );
    Route::post( 'notification/delete', 'API\NotificationController@delete' );

    //PAYMENT
    Route::get( 'payment/config', 'API\PaymentController@config' );
    Route::post( 'payment/checkout', 'API\PaymentController@checkout' );
});