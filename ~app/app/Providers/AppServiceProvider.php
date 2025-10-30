<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    Schema::defaultStringLength(191);

    Paginator::useBootstrap();

    if (!$this->app->runningInConsole()) {
      $request = $this->app['request'];

      $forwardedProto = $request->headers->get('X-Forwarded-Proto');
      $scheme = $forwardedProto ? trim(explode(',', $forwardedProto)[0]) : $request->getScheme();

      $host = $request->getHttpHost();
      $basePath = trim($request->getBaseUrl(), '/');
      $rootUrl = sprintf('%s://%s', $scheme, $host) . ($basePath ? '/' . $basePath : '');

      URL::forceRootUrl($rootUrl);

      if (Str::startsWith($rootUrl, 'https://')) {
        URL::forceScheme('https');
      }

      config([
        'app.url' => $rootUrl,
        'app.asset_url' => $rootUrl,
      ]);
    }

  View::composer(['admin::layouts.header'], function ($view) {
      $action = app('request')->route()->getAction();
      $controller = class_basename($action['controller']);
      list($controller, $action) = explode('@', $controller);

      $view->with(compact('controller', 'action'));
    });

    Validator::extend('recaptcha', 'App\\Validators\\ReCaptcha@validate');
  }
}
