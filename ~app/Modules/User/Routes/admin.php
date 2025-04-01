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
  Route::resource('user', 'UserController', ['except' => ['show']]);
  Route::name('user.')->group(function () {
    Route::prefix('user')->group(function () {
      Route::resource('change-password', 'ChangePasswordController', ['except' => ['index', 'create', 'store', 'show', 'destroy']]);
      Route::resource('role', 'RoleController', ['except' => ['show']]);
      Route::name('role.')->group(function () {
        Route::prefix('role')->group(function () {
          Route::get('sync', 'RoleController@sync')->name('sync');
        });
      });
    });
  });
});
