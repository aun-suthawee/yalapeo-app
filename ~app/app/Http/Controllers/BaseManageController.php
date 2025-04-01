<?php

namespace App\Http\Controllers;

class BaseManageController extends BaseController
{
  public function init(array $config = [])
  {
    if (isset($config['permission_prefix'])) {
      $permission_prefix = $config['permission_prefix'];

      $this->middleware('permission:' . $permission_prefix . '@*list|' . $permission_prefix . '@*create|' . $permission_prefix . '@*edit|' . $permission_prefix . '@*delete', ['only' => ['index', 'show']]);
      $this->middleware('permission:' . $permission_prefix . '@*create', ['only' => ['create', 'store']]);
      $this->middleware('permission:' . $permission_prefix . '@*edit', ['only' => ['edit', 'update']]);
      $this->middleware('permission:' . $permission_prefix . '@*delete', ['only' => ['destroy']]);
    }

    $this->data = [
      'body' => [
        'app_title' => [
          'h1' => isset($config['body']['app_title']['h1']) ? $config['body']['app_title']['h1'] : '',
          'p' => isset($config['body']['app_title']['p']) ? $config['body']['app_title']['p'] : '',
        ]
      ],
      'permission_prefix' => isset($permission_prefix) ? $permission_prefix : ''
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

    if (isset($this->breadcrumb['class_name'])) {
      $classPath = "\Modules\\$getModuleCurrentName\Breadcrumb\Admin\\" . $this->breadcrumb['class_name'];
      if (class_exists($classPath)) {
        $classBreadcrumb = new $classPath();
        if (method_exists($classBreadcrumb, $getCurrentAction)) {
          $breadcrumb = $classBreadcrumb->$getCurrentAction();
        }
      }
    } else {
      $classPath = "\App\Breadcrumb\BaseBreadcrumb";
      if (class_exists($classPath)) {
        $classBreadcrumb = new $classPath();
        $classBreadcrumb->init(['prefix' => strtolower($getModuleCurrentName)]);
        if (method_exists($classBreadcrumb, $getCurrentAction)) {
          $breadcrumb = $classBreadcrumb->$getCurrentAction();
        }
      }
    }

    return $breadcrumb;
  }
}
