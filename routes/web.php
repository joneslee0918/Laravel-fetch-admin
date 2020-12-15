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
	Route::resource('home', 'WEB\HomeController', ['except' => ['show']]);

	//user manage
	Route::resource('user', 'WEB\UserController', ['except' => ['show']]);

	//category manage
	Route::resource('category', 'WEB\CategoryController', ['except' => ['show']]);

	//category manage
	Route::resource('breed', 'WEB\BreedController', ['except' => ['show']]);

	//ads manage
	Route::resource('ads', 'WEB\AdsController', ['except' => ['show']]);
});