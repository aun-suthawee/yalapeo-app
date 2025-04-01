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

Route::middleware('admin')->prefix('admin')->namespace('Admin')->name('admin.')->group(function () {
    Route::resource('account', 'AccountController', ['except' => ['show', 'store', 'create', 'destroy', 'edit']]);
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('password', 'AccountController@password')->name('change-password.index');
        Route::put('password/{id}', 'AccountController@passwordUpdate')->name('change-password.update');
    });
});
