<?php

namespace Modules\Setting\Providers;

use Cache;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Setting\Entities\Aside;
use Modules\Setting\Repositories\MetaRepository;

class SettingServiceProvider extends ServiceProvider
{

  private function expiresAt()
  {
    return Carbon::now()->addMinutes(30);
  }

  /**
   * @return void
   */
  private function getViewComposer()
  {
    View::composer([
      'home::layouts.master',
      'home::index',
      'home::intro',
      'admin::layouts.master',
      'admin::login'
    ], function ($view) {
      $cacheMeta = Cache::remember('cacheMeta', $this->expiresAt(), function () {
        $model = new MetaRepository();

        return $model->get(1);
      });

      return $view->with(compact('cacheMeta'));
    });

    View::composer(['admin::layouts.aside'], function ($view) {
      //$cacheNavigation = Cache::remember('cacheNavigation', $this->expiresAt(), function () {
      //  return Aside::getNavigationSidebar();
      //});
      $cacheNavigation = Aside::getNavigationSidebar();

      return $view->with(compact('cacheNavigation'));
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
    $this->loadMigrationsFrom(module_path('Setting', 'Database/Migrations'));
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
      module_path('Setting', 'Config/config.php') => config_path('setting.php'),
    ], 'config');
    $this->mergeConfigFrom(
      module_path('Setting', 'Config/config.php'),
      'setting'
    );
  }

  /**
   * Register views.
   *
   * @return void
   */
  public function registerViews()
  {
    $viewPath = resource_path('views/modules/setting');

    $sourcePath = module_path('Setting', 'Resources/views');

    $this->publishes([
      $sourcePath => $viewPath
    ], 'views');

    $this->loadViewsFrom(array_merge(array_map(function ($path) {
      return $path . '/modules/setting';
    }, \Config::get('view.paths')), [$sourcePath]), 'setting');
  }

  /**
   * Register translations.
   *
   * @return void
   */
  public function registerTranslations()
  {
    $langPath = resource_path('lang/modules/setting');

    if (is_dir($langPath)) {
      $this->loadTranslationsFrom($langPath, 'setting');
    } else {
      $this->loadTranslationsFrom(module_path('Setting', 'Resources/lang'), 'setting');
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
