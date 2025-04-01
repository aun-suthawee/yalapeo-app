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

Route::resource('webboard', 'WebboardController', ['except' => ['edit', 'update']]);

Route::prefix('webboard/{webboard}')->name('webboard.')->group(function () {
  Route::resource('answer', 'AnswerController', ['except' => ['index', 'edit', 'update', 'show']]);
});
