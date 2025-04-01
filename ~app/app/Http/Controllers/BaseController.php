<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use DB, Cache;

class BaseController extends Controller
{
  protected $data;
  protected $breadcrumb;

  public function init(array $config = [])
  {
    $this->data = [
      'body' => [
        'app_title' => [
          'h1' => isset($config['body']['app_title']['h1']) ? $config['body']['app_title']['h1'] : '',
          'p' => isset($config['body']['app_title']['p']) ? $config['body']['app_title']['p'] : '',
        ]
      ],
    ];

    if (isset($config['breadcrumb']) && isset($config['breadcrumb']['class_name'])) {
      $this->breadcrumb = [
        'class_name' => $config['breadcrumb']['class_name']
      ];
    }

    $this->setCacheProvinces();
  }

  /**
   * @return array
   */
  public function setBreadcrumb()
  {
    $breadcrumb = [];

    $getModuleCurrentName = $this->getModuleCurrentName();
    $getCurrentAction = $this->getCurrentAction();
    $setClassName = isset($this->breadcrumb['class_name']) ? $this->breadcrumb['class_name'] : 'Breadcrumb';

    $classPath = "\Modules\\$getModuleCurrentName\Breadcrumb\\$setClassName";
    if (class_exists($classPath)) {
      $classBreadcrumb = new $classPath;
      if (method_exists($classBreadcrumb, $getCurrentAction)) {
        $breadcrumb = $classBreadcrumb->$getCurrentAction();
      }
    }

    return $breadcrumb;
  }

  /**
   * @return string
   */
  public function getModuleCurrentName()
  {
    $action = Route::getCurrentRoute()->getActionName();
    $exp = explode("\\", $action);

    return ucfirst($exp[1]);
  }

  /**
   * @return string
   */
  public function getCurrentAction()
  {
    return Route::getCurrentRoute()->getActionMethod();
  }

  public function setCacheProvinces()
  {
    Cache::remember('provinces', Carbon::now()->addMinutes(30), function () {
      return DB::table('provinces')->orderBy('name_th', 'asc')->get();
    });
  }

  public function getProvinces()
  {
    return Cache::get('provinces');
  }

  /**
   * @param string $view
   * @param array $data
   *
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function render(string $view, array $data = [])
  {
    if (empty($this->data)) {
      $this->data = [];
    }

    $data = array_merge($data, $this->data);
    $data['breadcrumb'] = $this->setBreadcrumb();
    $data['module_name'] = strtolower($this->getModuleCurrentName());

    return view($view, $data);
  }
}
