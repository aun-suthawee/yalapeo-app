<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public API routes with rate limiting
Route::middleware(['throttle:60,1'])->prefix('sandbox')->name('api.sandbox.')->group(function() {
    // Chart data endpoint (60 requests per minute)
    Route::get('/academic-results/chart-data', 'AcademicResultsController@getChartData')
        ->name('academic-results.chart-data');
    
    // Schools listing (60 requests per minute)
    Route::get('/schools', 'SandboxController@index')->name('schools.index');
    
    // Innovations listing (60 requests per minute)
    Route::get('/innovations', 'SandboxController@dashboard')->name('innovations.index');
});

// Authenticated API routes with stricter rate limiting
Route::middleware(['auth:api', 'throttle:30,1'])->prefix('sandbox')->name('api.sandbox.')->group(function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Export endpoints (30 requests per minute for authenticated users)
    Route::get('/export/schools', 'SandboxController@exportSchools')->name('export.schools');
    Route::get('/export/academic-results', 'AcademicResultsController@exportResults')->name('export.academic-results');
});