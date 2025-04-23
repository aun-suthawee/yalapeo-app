<?php

namespace Modules\YrpDashboard\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\View\View;

class YrpDashboardController extends Controller
{
    public function index(): View
    {
        return view('yrpdashboard::index');
    }
    
    public function strategy(): View
    {
        return view('yrpdashboard::strategy');
    }
}
