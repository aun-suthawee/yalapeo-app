<?php

namespace Modules\Menu\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Carbon;
use Modules\Menu\Repositories\MenutopRepository as Repository;
use Cache;

class MenuServiceProvider extends ServiceProvider
{
  private function expiresAt()
  {
    return Carbon::now()->addMinutes(30);
  }

  private function getViewComposer()
  {
    View::composer(['home::layouts.header'], function ($view) {
      $menu_top = Cache::remember('menu_top', $this->expiresAt(), function () {
        $menu = new Repository();
        return $menu->prepare();
      });
      return $view->with(compact('menu_top'));
    });
  }

  /**
   * Boot the application events.
   *
   * @return void
   */
  public function boot()
  {
    $this->registerTranslations();
    $this->registerConfig();
    $this->registerViews();
    $this->loadMigrationsFrom(module_path('Menu', 'Database/Migrations'));
    $this->getViewComposer();
  }

  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register()
  {
    $this->app->register(RouteServiceProvider::class);
  }

  /**
   * Register config.
   *
   * @return void
   */
  protected function registerConfig()
  {
    $this->publishes([
      module_path('Menu', 'Config/config.php') => config_path('menu.php'),
    ], 'config');
    $this->mergeConfigFrom(
      module_path('Menu', 'Config/config.php'),
      'menu'
    );
  }

  /**
   * Register views.
   *
   * @return void
   */
  public function registerViews()
  {
    $viewPath = resource_path('views/modules/menu');

    $sourcePath = module_path('Menu', 'Resources/views');

    $this->publishes([
      $sourcePath => $viewPath
    ], 'views');

    $this->loadViewsFrom(array_merge(\Config::get('view.paths'), [$sourcePath]), 'menu');
  }

  /**
   * Register translations.
   *
   * @return void
   */
  public function registerTranslations()
  {
    $langPath = resource_path('lang/modules/menu');

    if (is_dir($langPath)) {
      $this->loadTranslationsFrom($langPath, 'menu');
    } else {
      $this->loadTranslationsFrom(module_path('Menu', 'Resources/lang'), 'menu');
    }
  }

  /**
   * Get the services provided by the provider.
   *
   * @return array
   */
  public function provides()
  {
    return [];
  }
}
