<?php

namespace Modules\Home\Http\Controllers;

use App\Http\Controllers\BaseViewController;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;

class IntroController extends BaseViewController
{

  public function __construct()
  {

    $this->init([
      'body' => [
        'title' => config('init.name'),
        'description' => _stripTags(config('init.address'))
      ]
    ]);
  }

  public function index()
  {
    $response = Http::get('https://backdrop.itopcybersoft.com');
    $result = $response->object();
    

    return $this->render('home::intro', compact('result'));
  }

  public function gotoHomePage()
  {
    $cookie = Cookie::has('__cookie_intro');
    if (!$cookie)
      Cookie::queue('__cookie_intro', true, 60 * 3);

    return redirect()->route('home.index');
  }
}
