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

Route::prefix('ita')->group(function () {
  Route::name('ita.')->group(function () {
    Route::get('/', 'ItaController@index')->name('index');
    Route::get('/{slug}', 'ItaController@show')->name('show');
  });
});

Route::prefix('lpa')->group(function () {
  Route::name('lpa.')->group(function () {
    Route::get('/', 'LpaController@index')->name('index');
    Route::get('/{slug}', 'LpaController@show')->name('show');
  });
});
