<?php

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

Auth::routes();

// Logout route
Route::get('/api/logout', function () {
    Auth::logout();
    return Redirect::to('login');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');

// User authenticated routes
Route::group(['middleware' => 'auth'], function () {
    // Application api routes
    Route::prefix('api')->group(function () {
        // Lists
        Route::resource('/lists', 'ListController');

        // Update List Total
        Route::post('/lists/{list}/total', 'ListController@total');

        // List Items
        Route::get('/items/{list}', 'ListItemController@index');
        Route::resource('/items', 'ListItemController');

        // Complete / Incomplete
        Route::post('/items/{item}/complete', 'ListItemController@complete');
        Route::post('/items/{item}/incomplete', 'ListItemController@incomplete');

        // Users
        Route::get('/user', 'UserController@index');
        Route::post('/account', 'UserController@account');
        Route::post('/password', 'UserController@password');
    });

    // Catch-all route
    Route::get('/{any}', 'HomeController@index')->where('any', '.*');
});
