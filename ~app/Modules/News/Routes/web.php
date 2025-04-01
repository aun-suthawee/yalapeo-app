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

Route::prefix('news')->name('news.')->group(function () {
  Route::get('/', 'NewsController@index')->name('index');
  Route::get('/sl-type/{type?}', 'NewsController@index')->name('oftype');
  Route::get('/{slug}', 'NewsController@show')->name('show');
  Route::get('/download/{id}-{download}&t={time}', 'NewsController@download')->name('download');
});
