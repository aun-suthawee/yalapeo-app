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
  Route::resource('banner/large', 'BannerController', [
    'except' => ['show'],
    'names' => 'banner.large'
  ]);
  Route::prefix('banner/large')->name('banner.large.')->group(function () {
    Route::post('sort', 'BannerController@sort')->name('sort');
  });

  Route::resource('banner/small', 'BannerSmallController', [
    'except' => ['show'],
    'names' => 'banner.small'
  ]);
  Route::prefix('banner/small')->name('banner.small.')->group(function () {
    Route::post('sort', 'BannerSmallController@sort')->name('sort');
  });
});
