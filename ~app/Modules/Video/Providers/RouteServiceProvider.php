<?php

namespace Modules\Video\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $moduleNamespace = 'Modules\Video\Http\Controllers';

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
        $this->mapAdminRoutes();
        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        if (!$this->isSubdomain(request()->getHost())) {
            Route::middleware('admin')
                ->prefix('admin')
                ->namespace($this->moduleNamespace . "\Admin")
                ->group(module_path('Video', 'Routes/admin.php'));

        }
    }

    protected function mapWebRoutes()
    {
        if (!$this->isSubdomain(request()->getHost())) {
            Route::middleware('web')
                ->namespace($this->moduleNamespace)
                ->group(module_path('Video', '/Routes/web.php'));
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
        $subdomains = ['yrp'];

        $baseDomain = 'yalapeo-app.test';

        // ถ้า host คือโดเมนหลัก จะคืนค่า false
        if ($host === $baseDomain) {
            return false;
        }

        // ตรวจสอบว่า host เป็น subdomain หรือไม่
        foreach ($subdomains as $subdomain) {
            if (strpos($host, $subdomain . '.') === 0) {
                return true;
            }
        }

        return false;
    }
}
