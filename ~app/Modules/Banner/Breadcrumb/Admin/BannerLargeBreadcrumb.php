<?php

namespace Modules\Banner\Breadcrumb\Admin;

use App\Breadcrumb\BaseBreadcrumb;

class BannerLargeBreadcrumb extends BaseBreadcrumb
{
  public function __construct()
  {
    $this->init([
      'prefix' => 'banner.large'
    ]);
  }
}
