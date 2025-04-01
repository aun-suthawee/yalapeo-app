<?php

namespace Modules\Webboard\Providers;

use Illuminate\Support\ServiceProvider;

class WebboardServiceProvider extends ServiceProvider
{
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
    $this->loadMigrationsFrom(module_path('Webboard', 'Database/Migrations'));
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
      module_path('Webboard', 'Config/config.php') => config_path('webboard.php'),
    ], 'config');
    $this->mergeConfigFrom(
      module_path('Webboard', 'Config/config.php'),
      'webboard'
    );
  }

  /**
   * Register views.
   *
   * @return void
   */
  public function registerViews()
  {
    $viewPath = resource_path('views/modules/webboard');

    $sourcePath = module_path('Webboard', 'Resources/views');

    $this->publishes([
      $sourcePath => $viewPath
    ], 'views');

    $this->loadViewsFrom(array_merge(\Config::get('view.paths'), [$sourcePath]), 'webboard');
  }

  /**
   * Register translations.
   *
   * @return void
   */
  public function registerTranslations()
  {
    $langPath = resource_path('lang/modules/webboard');

    if (is_dir($langPath)) {
      $this->loadTranslationsFrom($langPath, 'webboard');
    } else {
      $this->loadTranslationsFrom(module_path('Webboard', 'Resources/lang'), 'webboard');
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
