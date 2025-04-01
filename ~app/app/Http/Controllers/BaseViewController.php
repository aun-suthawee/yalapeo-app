<?php

/**
 * Created by iTopCyberSoft.
 * Description : สำหรับแสดงผลหน้าเว็บไซต์
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

class BaseViewController extends Controller
{
  protected $data;
  protected $breadcrumb;

  public function init(array $config = [])
  {
    $this->data = [
      'body' => [
        'title' => $config['body']['title'] ?? '',
        'description' => $config['body']['description'] ?? '',
      ],
    ];

    if (isset($config['breadcrumb']['class_name'])) {
      $this->breadcrumb = [
        'class_name' => $config['breadcrumb']['class_name']
      ];
    }
  }

  /**
   * @return array
   */
  public function setBreadcrumb(): array
  {
    $breadcrumb = [];

    $getModuleCurrentName = $this->getModuleCurrentName();
    $getCurrentAction = $this->getCurrentAction();
    $setClassName = $this->breadcrumb['class_name'] ?? 'Breadcrumb';

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
  public function getCurrentAction(): string
  {
    return Route::getCurrentRoute()->getActionMethod();
  }

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
