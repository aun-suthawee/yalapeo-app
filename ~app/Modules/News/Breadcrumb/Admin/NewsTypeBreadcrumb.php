<?php

namespace Modules\News\Breadcrumb\Admin;

use App\Breadcrumb\BaseBreadcrumb;

class NewsTypeBreadcrumb extends BaseBreadcrumb
{
  public function __construct()
  {
    $this->init([
      'prefix' => 'news.type'
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
        'label' => 'ข้อมูลรายการข่าวสาร',
        'link' => route('admin.news.index')
      ],
      [
        'label' => 'ข้อมูลรายการ',
        'link' => ''
      ]
    ];
  }

  public function create()
  {
    return [
      [
        'label' => 'Dashboard',
        'link' => route('admin.dashboard.index')
      ],
      [
        'label' => 'ข้อมูลรายการข่าวสาร',
        'link' => route('admin.news.index')
      ],
      [
        'label' => 'ข้อมูลรายการ',
        'link' => route('admin.' . $this->prefix . '.index')
      ],
      [
        'label' => 'เพิ่มข้อมูล',
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
        'label' => 'ข้อมูลรายการข่าวสาร',
        'link' => route('admin.news.index')
      ],
      [
        'label' => 'ข้อมูลรายการ',
        'link' => route('admin.' . $this->prefix . '.index')
      ],
      [
        'label' => 'แก้ไขรายการ',
        'link' => ''
      ]
    ];
  }
}
