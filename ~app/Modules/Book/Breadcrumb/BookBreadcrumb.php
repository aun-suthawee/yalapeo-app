<?php

namespace Modules\Book\Breadcrumb;

use App\Breadcrumb\BaseBreadcrumb;
use Modules\Book\Repositories\BookRepository as Repository;

class BookBreadcrumb extends BaseBreadcrumb
{
  protected $repository;

  public function __construct()
  {
    $this->init([
      'prefix' => 'book'
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
        'label' => 'หนังสือทั้งหมด',
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
        'label' => 'หนังสือทั้งหมด',
        'link' => route($this->prefix . '.index')
      ],
      [
        'label' => $result->title,
        'link' => ''
      ]
    ];
  }
}
