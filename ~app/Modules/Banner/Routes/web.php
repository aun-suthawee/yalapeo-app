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

Route::prefix('banner')->group(function () {
  Route::name('banner.large.')->group(function () {
    Route::get('lg/', 'BannerController@index')->name('index');
    Route::get('lg/{slug}', 'BannerController@show')->name('show');
  });

  Route::name('banner.small.')->group(function () {
    Route::get('sm/', 'BannerSmallController@index')->name('index');
    Route::get('sm/{slug}', 'BannerSmallController@show')->name('show');
  });
});
