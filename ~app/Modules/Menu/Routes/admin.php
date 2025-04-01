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
  Route::resource('menu-top', 'MenutopController', ['except' => ['show']]);
  Route::prefix('menu-top')->name('menu-top.')->group(function () {
    Route::post('sort', 'MenutopController@sort')->name('sort');
  });

  Route::resource('menu-side', 'MenusideController', ['except' => ['show']]);
  Route::prefix('menu-side')->name('menu-side.')->group(function () {
    Route::post('sort', 'MenusideController@sort')->name('sort');

    Route::post('/attach-sort/{id}', 'MenusideController@attachSort')->name('attach-sort');
    Route::post('/attach-destroy/{id}', 'MenusideController@attachDestroy')->name('attach-destroy');
  });
});
