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

Route::prefix('sandbox')->name('sandbox.')->group(function() {
    // Public routes (ไม่ต้อง login)
    Route::get('/', 'SandboxController@dashboard')->name('dashboard');
    Route::get('/infographic', 'SandboxController@infographic')->name('infographic');
    
    Route::get('/innovation-image/{schoolId}/{filename}', function($schoolId, $filename) {
        $folder = 'innovations/' . gen_folder($schoolId);
        $path = storage_path('app/public/' . $folder . '/' . $filename);
        
        if (!file_exists($path)) {
            abort(404);
        }
        
        $file = file_get_contents($path);
        $type = mime_content_type($path);
        $lastModified = filemtime($path);
        
        return response($file, 200)
            ->header('Content-Type', $type)
            ->header('Cache-Control', 'public, max-age=31536000, immutable')
            ->header('Last-Modified', gmdate('D, d M Y H:i:s', $lastModified) . ' GMT')
            ->header('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
    })->name('innovation.image');
    
    // School Management Routes
    Route::prefix('schools')->name('schools.')->group(function() {
        Route::get('/', 'SandboxController@index')->name('index');
        
        Route::middleware(['auth'])->group(function() {
            Route::get('/create', 'SandboxController@create')->name('create');
            Route::post('/', 'SandboxController@store')->name('store');
            Route::get('/{id}/edit', 'SandboxController@edit')->name('edit');
            Route::put('/{id}', 'SandboxController@update')->name('update');
            Route::delete('/{id}', 'SandboxController@destroy')->name('destroy');
        });
        
        Route::get('/{id}', 'SandboxController@show')->name('show');
        
        // Innovation Management Routes
        Route::get('/{schoolId}/innovations', 'SandboxController@showInnovations')->name('innovations');
        
        Route::middleware(['auth'])->group(function() {
            Route::get('/{schoolId}/innovations/create', 'SandboxController@createInnovation')->name('innovations.create');
            Route::post('/{schoolId}/innovations', 'SandboxController@storeInnovation')->name('innovations.store');
            Route::get('/{schoolId}/innovations/{innovationId}/edit', 'SandboxController@editInnovation')->name('innovations.edit');
            Route::put('/{schoolId}/innovations/{innovationId}', 'SandboxController@updateInnovation')->name('innovations.update');
            Route::delete('/{schoolId}/innovations/{innovationId}', 'SandboxController@destroyInnovation')->name('innovations.destroy');
        });
        
        Route::get('/{schoolId}/innovations/{innovationId}', 'SandboxController@getInnovation')->name('innovations.get')->where('innovationId', '[0-9]+');
        
        // Vision Video Management Routes
        Route::prefix('{schoolId}/vision-videos')->name('vision-videos.')->group(function() {
            Route::middleware(['auth'])->group(function() {
                Route::get('/create', 'SandboxController@createVisionVideo')->name('create');
                Route::post('/', 'SandboxController@storeVisionVideo')->name('store');
                Route::get('/{videoId}/edit', 'SandboxController@editVisionVideo')->name('edit');
                Route::put('/{videoId}', 'SandboxController@updateVisionVideo')->name('update');
                Route::delete('/{videoId}', 'SandboxController@destroyVisionVideo')->name('destroy');
            });
        });
        
        // Gallery Management Routes
        Route::prefix('{schoolId}/galleries')->name('galleries.')->group(function() {
            Route::get('/', 'GalleryController@index')->name('index');
            
            Route::middleware(['auth'])->group(function() {
                Route::get('/create', 'GalleryController@create')->name('create');
                Route::post('/', 'GalleryController@store')->name('store');
                Route::get('/{id}/edit', 'GalleryController@edit')->name('edit');
                Route::put('/{id}', 'GalleryController@update')->name('update');
                Route::delete('/{id}', 'GalleryController@destroy')->name('destroy');
            });
            
            Route::get('/{id}', 'GalleryController@show')->name('show');
        });
    });
    
    // Vision Videos - Public viewing routes
    Route::get('/vision-videos', 'SandboxController@allVisionVideos')->name('vision-videos.all');
    
    // Publication Management Routes
    Route::prefix('publications')->name('publications.')->group(function() {
        Route::get('/', 'PublicationController@index')->name('index');
        Route::get('/{id}', 'PublicationController@show')->name('show');
        Route::get('/pdf/{filename}', 'PublicationController@viewPdf')->name('pdf');
        
        Route::middleware(['auth'])->group(function() {
            Route::get('/create/new', 'PublicationController@create')->name('create');
            Route::post('/', 'PublicationController@store')->name('store');
            Route::get('/{id}/edit', 'PublicationController@edit')->name('edit');
            Route::put('/{id}', 'PublicationController@update')->name('update');
            Route::delete('/{id}', 'PublicationController@destroy')->name('destroy');
        });
    });

    // Academic Results Routes
    Route::prefix('academic-results')->name('academic-results.')->group(function() {
        Route::get('/', 'AcademicResultsController@index')->name('index');
        Route::middleware(['throttle:120,1'])->get('/chart-data', 'AcademicResultsController@getChartData')->name('chart-data');
        
        Route::middleware(['auth'])->group(function() {
            Route::get('/schools/{school}/edit', 'AcademicResultsController@edit')->name('edit');
            Route::put('/schools/{school}', 'AcademicResultsController@update')->name('update');
            Route::post('/schools/{school}/submit', 'AcademicResultsController@submit')->name('submit');
            Route::delete('/schools/{school}', 'AcademicResultsController@destroy')->name('destroy');
            
            // Export routes (authenticated only)
            Route::middleware(['throttle:30,1'])->group(function() {
                Route::get('/export/csv', 'AcademicResultsController@exportCSV')->name('export.csv');
                Route::get('/export/excel', 'AcademicResultsController@exportExcel')->name('export.excel');
            });
        });
    });
    
    // School Export Routes (authenticated only)
    Route::middleware(['auth', 'throttle:30,1'])->group(function() {
        Route::get('/schools/export/csv', 'SandboxController@exportSchoolsCSV')->name('schools.export.csv');
        Route::get('/schools/export/excel', 'SandboxController@exportSchoolsExcel')->name('schools.export.excel');
    });

    // Experiment Routes (What-If Analysis)
    Route::prefix('experiments')->name('experiments.')->group(function() {
        // Public share link
        Route::get('/share/{token}', 'ExperimentController@share')->name('share');
        
        Route::middleware(['auth'])->group(function() {
            // CRUD routes
            Route::get('/', 'ExperimentController@index')->name('index');
            Route::get('/create', 'ExperimentController@create')->name('create');
            Route::post('/', 'ExperimentController@store')->name('store');
            Route::get('/{experiment}/edit', 'ExperimentController@edit')->name('edit');
            Route::put('/{experiment}', 'ExperimentController@update')->name('update');
            Route::delete('/{experiment}', 'ExperimentController@destroy')->name('destroy');
            Route::get('/{experiment}', 'ExperimentController@show')->name('show');
            
            // Duplicate experiment
            Route::post('/{experiment}/duplicate', 'ExperimentController@duplicate')->name('duplicate');
            
            // Scenario management (AJAX endpoints)
            Route::post('/{experiment}/scenarios', 'ExperimentController@addScenario')->name('scenarios.add');
            Route::get('/{experiment}/scenarios/{scenario}', 'ExperimentController@getScenario')->name('scenarios.get');
            Route::put('/{experiment}/scenarios/order', 'ExperimentController@updateScenarioOrder')->name('scenarios.order');
            Route::put('/{experiment}/scenarios/{scenario}', 'ExperimentController@updateScenario')->name('scenarios.update');
            Route::delete('/{experiment}/scenarios/{scenario}', 'ExperimentController@deleteScenario')->name('scenarios.delete');
            
            // Run calculations
            Route::post('/{experiment}/scenarios/{scenario}/run', 'ExperimentController@runScenario')->name('scenarios.run');
            Route::post('/{experiment}/run-all', 'ExperimentController@runAllScenarios')->name('run-all');
            
            // Get comparison data (for charts)
            Route::get('/{experiment}/comparison-data', 'ExperimentController@getComparisonData')->name('comparison-data');

            // Add parameter to scenario (drag-drop)
            Route::post('/{experiment}/scenarios/{scenario}/parameter', 'ExperimentController@addParameterToScenario')->name('scenarios.addParameter');
            Route::delete('/{experiment}/scenarios/{scenario}/parameter/{paramKey}', 'ExperimentController@removeParameterFromScenario')->name('scenarios.removeParameter');
            
            // Export & Share
            Route::get('/{experiment}/export/pdf', 'ExperimentController@exportPDF')->name('export.pdf');
            Route::post('/{experiment}/export/excel', 'ExperimentController@exportExcel')->name('export.excel');
            Route::post('/{experiment}/send-email', 'ExperimentController@sendEmail')->name('send-email');
            Route::post('/{experiment}/send-batch', 'ExperimentController@sendBatchEmail')->name('send-batch');
        });
    });
});