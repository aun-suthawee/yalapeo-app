<?php

namespace Modules\Menu\Breadcrumb\Admin;

use App\Breadcrumb\BaseBreadcrumb;

class MenutopBreadcrumb extends BaseBreadcrumb
{
  public function __construct()
  {
    $this->init([
      'prefix' => 'menu-top'
    ]);
  }
}
