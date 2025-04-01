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

Route::prefix('content')->name('menu-side.')->group(function () {
  Route::get('/{slug}', 'MenusideController@show')->name('show');
  Route::get('/download/{id}-{download}&t={time}', 'MenusideController@download')->name('download');
});
