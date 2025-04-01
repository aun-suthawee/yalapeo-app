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

Route::name('admin.')->group(function () {
  Route::resource('news', 'NewsController', ['except' => ['show']]);
  Route::name('news.')->group(function () {
    Route::prefix('news')->group(function () {
      Route::resource('type', 'NewsTypeController', ['except' => ['show']]);
      Route::name('type.')->group(function () {
        Route::prefix('type')->group(function () {
          Route::post('sort', 'NewsTypeController@sort')->name('sort');
        });
      });
      Route::get('sl-type/{type?}', 'NewsController@index')->name('oftype');

      Route::post('/attach-sort/{id}', 'NewsController@attachSort')->name('attach-sort');
      Route::post('/attach-destroy/{id}', 'NewsController@attachDestroy')->name('attach-destroy');
    });
  });
});
