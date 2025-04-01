<?php

namespace Modules\Box\Providers;

use Illuminate\Support\ServiceProvider;

class BoxServiceProvider extends ServiceProvider
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
    $this->loadMigrationsFrom(module_path('Box', 'Database/Migrations'));
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
      module_path('Box', 'Config/config.php') => config_path('box.php'),
    ], 'config');
    $this->mergeConfigFrom(
      module_path('Box', 'Config/config.php'),
      'box'
    );
  }

  /**
   * Register views.
   *
   * @return void
   */
  public function registerViews()
  {
    $viewPath = resource_path('views/modules/box');

    $sourcePath = module_path('Box', 'Resources/views');

    $this->publishes([
      $sourcePath => $viewPath
    ], 'views');

    $this->loadViewsFrom(array_merge(\Config::get('view.paths'), [$sourcePath]), 'box');
  }

  /**
   * Register translations.
   *
   * @return void
   */
  public function registerTranslations()
  {
    $langPath = resource_path('lang/modules/box');

    if (is_dir($langPath)) {
      $this->loadTranslationsFrom($langPath, 'box');
    } else {
      $this->loadTranslationsFrom(module_path('Box', 'Resources/lang'), 'box');
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
