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
    Route::resource('gallery', 'GalleryController', ['except' => ['show']]);
    Route::prefix('gallery')->name('gallery.')->group(function () {
        Route::post('sort', 'GalleryController@sort')->name('sort');
        Route::post('slider-sort/{id}', 'GalleryController@sliderSort')->name('slider-sort');
        Route::post('slider-destroy/{id}', 'GalleryController@sliderDestroy')->name('slider-destroy');
    });
});
