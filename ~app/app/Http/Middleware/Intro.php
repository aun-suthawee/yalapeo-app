<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Cookie;

use Closure;

class Intro
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    $cookie = Cookie::has('__cookie_intro');
    if (!$cookie)
      return redirect()->route('home.intro');

    return $next($request);
  }
}
