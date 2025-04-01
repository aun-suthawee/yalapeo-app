<?php

namespace Modules\Banner\Providers;

use Illuminate\Support\ServiceProvider;

class BannerServiceProvider extends ServiceProvider
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
    $this->loadMigrationsFrom(module_path('Banner', 'Database/Migrations'));
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
      module_path('Banner', 'Config/config.php') => config_path('banner.php'),
    ], 'config');
    $this->mergeConfigFrom(
      module_path('Banner', 'Config/config.php'),
      'banner'
    );
  }

  /**
   * Register views.
   *
   * @return void
   */
  public function registerViews()
  {
    $viewPath = resource_path('views/modules/banner');

    $sourcePath = module_path('Banner', 'Resources/views');

    $this->publishes([
      $sourcePath => $viewPath
    ], 'views');

    $this->loadViewsFrom(array_merge(\Config::get('view.paths'), [$sourcePath]), 'banner');
  }

  /**
   * Register translations.
   *
   * @return void
   */
  public function registerTranslations()
  {
    $langPath = resource_path('lang/modules/banner');

    if (is_dir($langPath)) {
      $this->loadTranslationsFrom($langPath, 'banner');
    } else {
      $this->loadTranslationsFrom(module_path('Banner', 'Resources/lang'), 'banner');
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
