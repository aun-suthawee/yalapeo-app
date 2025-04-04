<?php

use Illuminate\Support\Facades\Route;
use Modules\TiktokVideo\Http\Controllers\Admin\AdminTiktokVideoController;
use Modules\TiktokVideo\Http\Controllers\TiktokVideoController;

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

Route::group(['prefix' => 'tiktokvideo'], function() {
    Route::get('/', [TiktokVideoController::class, 'index'])->name('tiktokvideo.index');
});

Route::prefix('admin/tiktokvideo')
    ->middleware(['auth']) 
    ->name('admin.tiktokvideo.')
    ->group(function() {
        Route::get('/', [AdminTiktokVideoController::class, 'index'])->name('index');
        Route::get('/create', [AdminTiktokVideoController::class, 'create'])->name('create');
        Route::post('/', [AdminTiktokVideoController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminTiktokVideoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminTiktokVideoController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminTiktokVideoController::class, 'destroy'])->name('destroy');
    });
