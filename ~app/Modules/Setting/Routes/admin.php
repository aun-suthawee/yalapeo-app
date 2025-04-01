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

Route::middleware('admin')->prefix('admin/setting')->group(function () {
  Route::namespace('Admin')->name('admin.setting.')->group(function () {
    Route::resource('meta', 'MetaController', ['except' => ['show', 'store', 'create', 'destroy', 'edit']]);
  });
});
