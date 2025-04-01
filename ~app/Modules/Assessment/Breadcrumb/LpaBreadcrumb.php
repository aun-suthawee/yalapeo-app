<?php

namespace Modules\Assessment\Breadcrumb;

use App\Breadcrumb\BaseBreadcrumb;
use Modules\Assessment\Repositories\LpaRepository;

class LpaBreadcrumb extends BaseBreadcrumb
{
  protected $repository;

  public function __construct()
  {
    $this->init([
      'prefix' => 'lpa'
    ]);

    $this->repository = new LpaRepository();
  }

  public function index()
  {
    return [
      [
        'label' => 'หน้าแรก',
        'link' => route('home.index')
      ],
      [
        'label' => 'LPA ทั้งหมด',
        'link' => ''
      ]
    ];
  }

  public function show()
  {
    $result = $this->repository->lpasOfYear(request()->slug);

    return [
      [
        'label' => 'หน้าแรก',
        'link' => route('home.index')
      ],
      [
        'label' => 'LPA ทั้งหมด',
        'link' => route($this->prefix . '.index')
      ],
      [
        'label' => $result->year,
        'link' => ''
      ]
    ];
  }
}
