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

Route::redirect('/', '/login');
Auth::routes();

Route::group(['middleware' => 'auth'], function () {

	//DASHBOARD
	Route::resource('dashboard', 'WEB\HomeController', ['except' => ['show']]);

	//PROFILE MANAGE
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'WEB\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'WEB\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'WEB\ProfileController@password']);
	
	//USER MANAGE
	Route::resource('user', 'WEB\UserController', ['except' => ['show']]);

	//CATEGORY MANAGE
	Route::resource('category', 'WEB\CategoryController', ['except' => ['show']]);

	//CATEGORY MANAGE
	Route::resource('breed', 'WEB\BreedController', ['except' => ['show']]);

	//ADS MANAGE
	Route::resource('ads', 'WEB\AdsController', ['except' => ['show']]);
	Route::post('ads/image/delete', 'WEB\AdsController@deleteImage');

	//CHAT MANAGE
	Route::resource('chat', 'WEB\ChatController', ['except' => ['show']]);
	Route::post('chat/messages', 'WEB\ChatController@getMessage');
	Route::post('chat/messages/delete', 'WEB\ChatController@deleteMessage');
});