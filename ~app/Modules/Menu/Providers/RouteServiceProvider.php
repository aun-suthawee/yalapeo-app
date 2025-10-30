<?php

namespace Modules\Menu\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
  /**
   * The module namespace to assume when generating URLs to actions.
   *
   * @var string
   */
  protected $moduleNamespace = 'Modules\Menu\Http\Controllers';

  /**
   * Called before routes are registered.
   *
   * Register any model bindings or pattern based filters.
   *
   * @return void
   */
  public function boot()
  {
    parent::boot();
    $this->registerPageRoute();
  }

  private function registerPageRoute()
  {
    $this->app->booted(function () {
      Route::get('/{slug}', '\Modules\Menu\Http\Controllers\MenutopController@show')
        ->middleware('web')
        ->name('menu-top.show');
    });
  }

  /**
   * Define the routes for the application.
   *
   * @return void
   */
  public function map()
  {
    $this->mapAdminRoutes();
    $this->mapWebRoutes();
  }

  /**
   * Define the "web" routes for the application.
   *
   * These routes all receive session state, CSRF protection, etc.
   *
   * @return void
   */
  protected function mapAdminRoutes()
  {
    Route::namespace($this->moduleNamespace)
      ->group(module_path('Menu', 'Routes/admin.php'));
  }

  protected function mapWebRoutes()
  {
    Route::middleware('web')
      ->namespace($this->moduleNamespace)
      ->group(module_path('Menu', 'Routes/web.php'));
  }
}
