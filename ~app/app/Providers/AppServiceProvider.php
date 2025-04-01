<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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

    app('view')->composer(['admin::layouts.header'], function ($view) {
      $action = app('request')->route()->getAction();
      $controller = class_basename($action['controller']);
      list($controller, $action) = explode('@', $controller);

      $view->with(compact('controller', 'action'));
    });

    Validator::extend('recaptcha', 'App\\Validators\\ReCaptcha@validate');
  }
}
