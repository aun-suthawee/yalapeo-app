<?php


namespace App\Breadcrumb;


class BaseBreadcrumb
{
  public $prefix;

  public function init(array $config = [])
  {
    if (isset($config['prefix'])) {
      $this->prefix = $config['prefix'];
    }
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
        'label' => 'ข้อมูลรายการ',
        'link' => route('admin.' . $this->prefix . '.index')
      ],
      [
        'label' => 'แก้ไขรายการ',
        'link' => ''
      ]
    ];
  }
  public function show()
  {
    return [
      [
        'label' => 'Dashboard',
        'link' => route('admin.dashboard.index')
      ],
      [
        'label' => 'ข้อมูลรายการ',
        'link' => route('admin.' . $this->prefix . '.index')
      ],
      [
        'label' => 'ดูรายการ',
        'link' => ''
      ]
    ];
  }
}
