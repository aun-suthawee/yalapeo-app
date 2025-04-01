<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @param  string|null  $guard
   * @return mixed
   */
  public function handle($request, Closure $next, $guard = null)
  {
    switch ($guard) {
      case 'admin':
        if (Auth::check()) {
          return redirect()->route('admin.dashboard.index');
        }
        break;
      default:
        return $next($request);
        break;
    }

    return $next($request);
  }
}
