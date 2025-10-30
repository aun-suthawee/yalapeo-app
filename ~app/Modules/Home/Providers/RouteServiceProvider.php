<?php

namespace Modules\Home\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
  /**
   * The module namespace to assume when generating URLs to actions.
   *
   * @var string
   */
  protected $moduleNamespace = 'Modules\Home\Http\Controllers';

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
  }

  /**
   * Define the routes for the application.
   *
   * @return void
   */
  public function map()
  {
    $this->mapWebRoutes();
  }

  /**
   * Define the "web" routes for the application.
   *
   * These routes all receive session state, CSRF protection, etc.
   *
   * @return void
   */
  protected function mapWebRoutes()
  {
    if (!$this->isSubdomain(request()->getHost())) {
      Route::middleware('web')
        ->namespace($this->moduleNamespace)
        ->group(module_path('Home', '/Routes/web.php'));
    }
  }

  /**
   * ตรวจสอบว่า hostname ที่ส่งมาเป็น subdomain หรือไม่
   *
   * @param string $host
   * @return bool
   */
  protected function isSubdomain($host)
  {
    // รายชื่อ subdomain ที่มีในระบบ
    $subdomains = ['ypr'];

    $baseDomain = parse_url(config('app.url'), PHP_URL_HOST) ?: parse_url(config('app.asset_url'), PHP_URL_HOST);
    $baseDomain = $baseDomain ?: $host;

    if ($host === $baseDomain) {
      return false;
    }

    foreach ($subdomains as $subdomain) {
      if (strpos($host, $subdomain . '.') === 0) {
        $expectedSuffix = '.' . $baseDomain;

        return substr($host, -strlen($expectedSuffix)) === $expectedSuffix;
      }
    }

    return false;
  }
}
