<?php

namespace Modules\Assessment\Breadcrumb;

use App\Breadcrumb\BaseBreadcrumb;
use Modules\Assessment\Repositories\ItaRepository;

class ItaBreadcrumb extends BaseBreadcrumb
{
  protected $repository;

  public function __construct()
  {
    $this->init([
      'prefix' => 'ita'
    ]);

    $this->repository = new ItaRepository();
  }

  public function index()
  {
    return [
      [
        'label' => 'หน้าแรก',
        'link' => route('home.index')
      ],
      [
        'label' => 'ITA ทั้งหมด',
        'link' => ''
      ]
    ];
  }

  public function show()
  {
    $result = $this->repository->itasOfYear(request()->slug);

    return [
      [
        'label' => 'หน้าแรก',
        'link' => route('home.index')
      ],
      [
        'label' => 'ITA ทั้งหมด',
        'link' => route($this->prefix . '.index')
      ],
      [
        'label' => $result->year,
        'link' => ''
      ]
    ];
  }
}
