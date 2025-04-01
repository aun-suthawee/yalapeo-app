<?php

namespace Modules\Page\Breadcrumb;

use App\Breadcrumb\BaseBreadcrumb;
use Modules\Page\Repositories\PageRepository as Repository;

class PageBreadcrumb extends BaseBreadcrumb
{
   protected $repository;

   public function __construct()
   {
      $this->init([
         'prefix' => 'page'
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
            'label' => 'Pageทั้งหมด',
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
            'label' => 'Pageทั้งหมด',
            'link' => route($this->prefix . '.index')
         ],
         [
            'label' => $result->title,
            'link' => ''
         ]
      ];
   }
}
