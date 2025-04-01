<?php

namespace Modules\Video\Providers;

use Illuminate\Support\ServiceProvider;

class VideoServiceProvider extends ServiceProvider
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
    $this->loadMigrationsFrom(module_path('Video', 'Database/Migrations'));
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
      module_path('Video', 'Config/config.php') => config_path('video.php'),
    ], 'config');
    $this->mergeConfigFrom(
      module_path('Video', 'Config/config.php'),
      'video'
    );
  }

  /**
   * Register views.
   *
   * @return void
   */ 
  public function registerViews()
  {
    $viewPath = resource_path('views/modules/video');

    $sourcePath = module_path('Video', 'Resources/views');

    $this->publishes([
      $sourcePath => $viewPath
    ], 'views');

    $this->loadViewsFrom(array_merge(\Config::get('view.paths'), [$sourcePath]), 'video');
  }

  /**
   * Register translations.
   *
   * @return void
   */
  public function registerTranslations()
  {
    $langPath = resource_path('lang/modules/video');

    if (is_dir($langPath)) {
      $this->loadTranslationsFrom($langPath, 'video');
    } else {
      $this->loadTranslationsFrom(module_path('Video', 'Resources/lang'), 'video');
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
