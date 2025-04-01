<?php

namespace Modules\Banner\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseManageController;
use Modules\Banner\Http\Requests\BannerStoreRequest;
use Modules\Banner\Repositories\BannerRepository as Repository;

class BannerController extends BaseManageController
{
  protected $repository;

  public function __construct(Repository $repository)
  {
    $this->repository = $repository;

    $this->init([
      'body' => [
        'app_title' => [
          'h1' => '<i class="fab fa-slideshare"></i> ภาพสไลด์',
          'p' => 'สามารถ เพิ่มและแก้ไขข้อมูลภาพสไลด์ได้'
        ]
      ],
      'breadcrumb' => [
        'class_name' => 'BannerLargeBreadcrumb'
      ],
      'permission_prefix' => 'banner_large'
    ]);
  }

  public function index()
  {
    $data['lists'] = $this->repository->paginate([], [
      'sort' => 'ASC',
      'id' => 'DESC',
    ]);
    $data['scripts'] = [
      asset('assets/@site_control/js/jui-sortable.min.js')
    ];

    return $this->render('banner::large.admin.index', $data);
  }

  public function create()
  {
    return $this->render('banner::large.admin.create');
  }

  public function store(BannerStoreRequest $request)
  {
    $this->repository->create($request->all());

    return $this->repository->redirectToList();
  }

  public function edit($id)
  {
    $data['result'] = $this->repository->get($id);

    return $this->render('banner::large.admin.edit', $data);
  }

  public function update(BannerStoreRequest $request, $id)
  {
    $this->repository->update($request->all(), $id);

    return $this->repository->redirectToList();
  }

  public function destroy($id)
  {
    $this->repository->destroy($id);

    return $this->repository->redirectToList();
  }

  public function sort(Request $request)
  {
    $this->repository->sort($request->all());

    return response()->json(['success' => 'updated successfully.']);
  }
}
