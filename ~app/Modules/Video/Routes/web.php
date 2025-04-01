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

Route::prefix('video')->name('video.')->group(function () {
  Route::get('/', 'VideoController@index')->name('index');
  Route::get('/{slug}', 'VideoController@show')->name('show');
});
