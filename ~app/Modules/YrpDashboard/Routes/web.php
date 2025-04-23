<?php

use Illuminate\Support\Facades\Route;
use Modules\YrpDashboard\Http\Controllers\YrpDashboardController;

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

// หน้าหลักของ subdomain
Route::get('/', [YrpDashboardController::class, 'index'])->name('yrp.dashboard.index');
Route::get('/strategy', [YrpDashboardController::class, 'strategy'])->name('yrp.dashboard.strategy');

Route::prefix('yrpdashboard')->group(function() {
    Route::get('/', 'YrpDashboardController@index');
});
