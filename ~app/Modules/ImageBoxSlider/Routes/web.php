<?php

use Illuminate\Support\Facades\Route;
use Modules\ImageBoxSlider\Http\Controllers\Admin\AdminImageBoxSliderController;
use Modules\ImageBoxSlider\Http\Controllers\ImageBoxSliderController;

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

Route::group(['prefix' => 'imageboxslider'], function() {
    Route::get('/', [ImageBoxSliderController::class, 'index'])->name('imageboxslider.index');
    Route::get('/{slug}', [ImageBoxSliderController::class, 'show'])->name('imageboxslider.show');
});

Route::prefix('admin/imageboxslider')
    ->middleware(['auth']) 
    ->name('admin.imageboxslider.')
    ->group(function() {
        Route::get('/', [AdminImageBoxSliderController::class, 'index'])->name('index');
        Route::get('/create', [AdminImageBoxSliderController::class, 'create'])->name('create');
        Route::post('/', [AdminImageBoxSliderController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminImageBoxSliderController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminImageBoxSliderController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminImageBoxSliderController::class, 'destroy'])->name('destroy');
    });
