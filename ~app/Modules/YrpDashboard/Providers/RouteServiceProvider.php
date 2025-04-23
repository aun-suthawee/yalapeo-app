<?php

namespace Modules\YrpDashboard\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * พาธของ controller namespace สำหรับโมดูลนี้
     */
    protected $moduleNamespace = 'Modules\YrpDashboard\Http\Controllers';

    /**
     * เรียกฟังก์ชันนี้เมื่อ provider ถูกโหลด
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * กำหนด route ของแอพพลิเคชัน
     */
    public function map()
    {
        $this->mapWebRoutes();
    }

    /**
     * กำหนด web routes ของโมดูลนี้
     * และตั้งค่า subdomain
     */
    protected function mapWebRoutes()
    {
        Route::domain('yrp.yalapeo-app.test')
            ->middleware('web')
            ->namespace($this->moduleNamespace)
            ->group(module_path('YrpDashboard', '/Routes/web.php'));
    }

    /**
     * กำหนด API routes ของโมดูลนี้
     */
    // protected function mapApiRoutes()
    // {
    //     Route::prefix('api')
    //         ->middleware('api')
    //         ->namespace($this->moduleNamespace)
    //         ->group(module_path('YrpDashboard', '/Routes/api.php'));
    // }
}
