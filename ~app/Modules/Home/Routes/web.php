<?php

use Illuminate\Support\Facades\Route;

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


Route::get('/yala-edu-fest-2025', function () {
    return view('home::pages.yala-edu-fest-2025');
})->name('home.yala-edu-fest');

Route::get('/บทความกฎหมาย', function () {
    return view('home::pages.judgment-publication');
})->name('home.judgment-publication');

Route::get('/intro', 'IntroController@index')->name('home.intro');
Route::get('/homepage', 'IntroController@gotoHomePage')->name('home.intro.to.homepage');
Route::get('/', 'HomeController@index')->middleware(['intro'])->name('home.index');
Route::post('/leave-message', 'LeaveMessageController@index')->name('home.leave-message');

