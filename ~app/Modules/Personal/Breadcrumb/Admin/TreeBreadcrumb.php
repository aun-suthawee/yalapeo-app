<?php

namespace Modules\Personal\Breadcrumb\Admin;

use App\Breadcrumb\BaseBreadcrumb;

class TreeBreadcrumb extends BaseBreadcrumb
{
  public function __construct()
  {
    $this->init([
      'prefix' => 'personal.struct'
    ]);
  }

  public function index()
  {
    return [
      [
        'label' => 'Dashboard',
        'link' => route('admin.dashboard.index')
      ],
      [
        'label' => 'ข้อมูลรายการ',
        'link' => route('admin.personal.index')
      ],
      [
        'label' => 'ข้อมูลโครงสร้าง',
        'link' => ''
      ]
    ];
  }

  public function edit()
  {
    return [
      [
        'label' => 'Dashboard',
        'link' => route('admin.dashboard.index')
      ],
      [
        'label' => 'ข้อมูลรายการ',
        'link' => route('admin.personal.index')
      ],
      [
        'label' => 'ข้อมูลรายการ',
        'link' => route('admin.personal.struct.index', request()->personal)
      ],
      [
        'label' => 'แก้ไขรายการ',
        'link' => ''
      ]
    ];
  }
}
