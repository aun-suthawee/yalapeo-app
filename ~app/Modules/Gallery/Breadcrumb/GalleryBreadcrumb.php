<?php

namespace Modules\Gallery\Breadcrumb;

use App\Breadcrumb\BaseBreadcrumb;
use Modules\Gallery\Repositories\GalleryRepository as Repository;

class GalleryBreadcrumb extends BaseBreadcrumb
{
    protected $repository;

    public function __construct()
    {
        $this->init([
            'prefix' => 'gallery'
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
                'label' => 'กิจกรรมทั้งหมด',
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
                'label' => 'กิจกรรมทั้งหมด',
                'link' => route($this->prefix . '.index')
            ],
            [
                'label' => $result->title,
                'link' => ''
            ]
        ];
    }
}
