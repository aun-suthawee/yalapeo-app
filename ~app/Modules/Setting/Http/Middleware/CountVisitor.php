<?php

namespace Modules\Setting\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Setting\Entities\Visits;

class CountVisitor
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle(Request $request, Closure $next)
  {
    $ip = $_SERVER['REMOTE_ADDR'];
    $created = date("Y-m-d");
    $time = time();

    $model = Visits::class;
    $q = $model::query();
    $q->where('ip', $ip);
    $q->where('created', $created);
    $count = $q->count();

    if ($count == 0) {
      $model::create([
        'ip'      => $ip,
        'created' => $created,
        'hits'    => 1,
        'online'  => $time,
      ]);
    } else {
      $model::where('ip', $ip)->where('created', $created)->update([
        'online' => $time,
        'hits' => \DB::raw("hits+1"),
      ]);
    }

    return $next($request);
  }
}
