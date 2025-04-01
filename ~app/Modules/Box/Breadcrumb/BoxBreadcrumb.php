<?php

namespace Modules\Box\Breadcrumb;

use App\Breadcrumb\BaseBreadcrumb;
use Modules\Box\Repositories\BoxRepository as Repository;

class BoxBreadcrumb extends BaseBreadcrumb
{
  protected $repository;

  public function __construct()
  {
    $this->init([
      'prefix' => 'box'
    ]);

    $this->repository = new Repository();
  }

  public function index()
  {
    return [
      [
        'label' => 'หน้าแรก',
        'link' => route('home.index')
      ],
      [
        'label' => 'Boxทั้งหมด',
        'link' => ''
      ]
    ];
  }

  public function show()
  {
    $result = $this->repository->getSlug(request()->slug);

    return [
      [
        'label' => 'หน้าแรก',
        'link' => route('home.index')
      ],
      [
        'label' => 'Boxทั้งหมด',
        'link' => route($this->prefix . '.index')
      ],
      [
        'label' => $result->title,
        'link' => ''
      ]
    ];
  }
}
