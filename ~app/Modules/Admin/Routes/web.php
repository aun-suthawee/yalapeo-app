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

Route::prefix('admin')->namespace('Admin')->name('admin.')->group(function() {
    Route::get('/', 'AdminController@index')->name('index');
    Route::namespace('Auth')->group(function() {
        Route::post('login', 'AdminLoginController@login')->name('login');
        Route::post('logout', 'AdminLoginController@logout')->name('logout');
    });
});