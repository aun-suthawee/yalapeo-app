<?php

namespace Modules\Assessment\Providers;

use Illuminate\Support\ServiceProvider;

class AssessmentServiceProvider extends ServiceProvider
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
    $this->loadMigrationsFrom(module_path('Assessment', 'Database/Migrations'));
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
      module_path('Assessment', 'Config/config.php') => config_path('assessment.php'),
    ], 'config');
    $this->mergeConfigFrom(
      module_path('Assessment', 'Config/config.php'),
      'assessment'
    );
  }

  /**
   * Register views.
   *
   * @return void
   */
  public function registerViews()
  {
    $viewPath = resource_path('views/modules/assessment');

    $sourcePath = module_path('Assessment', 'Resources/views');

    $this->publishes([
      $sourcePath => $viewPath
    ], 'views');

    $this->loadViewsFrom(array_merge(\Config::get('view.paths'), [$sourcePath]), 'assessment');
  }

  /**
   * Register translations.
   *
   * @return void
   */
  public function registerTranslations()
  {
    $langPath = resource_path('lang/modules/assessment');

    if (is_dir($langPath)) {
      $this->loadTranslationsFrom($langPath, 'assessment');
    } else {
      $this->loadTranslationsFrom(module_path('Assessment', 'Resources/lang'), 'assessment');
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
