<?php

namespace Modules\Menu\Http\Controllers\Admin;

use App\Http\Controllers\BaseManageController;
use Illuminate\Http\Request;
use Modules\Menu\Http\Requests\MenutopStoreRequest;
use Modules\Menu\Repositories\MenutopRepository as Repository;
use Cache;

class MenutopController extends BaseManageController
{
  protected $repository;

  public function __construct(Repository $repository)
  {
    $this->repository = $repository;

    $this->init([
      'body' => [
        'app_title' => [
          'h1' => '<i class="fab fa-monero"></i> เมนูด้านบน',
          'p' => 'สามารถ เพิ่มและแก้ไขข้อมูลเมนูด้านบนได้',
        ],
      ],
      'breadcrumb' => [
        'class_name' => 'MenutopBreadcrumb'
      ],
      'permission_prefix' => 'menu_top'
    ]);
  }

  public function index()
  {
    $data["lists"] = $this->repository->prepare();
    $data['scripts'] = [
      asset('assets/@site_control/js/jui-sortable.min.js')
    ];

    return $this->render('menu::menu_top.admin.index', $data);
  }

  public function create()
  {
    $data["lists"] = $this->repository->prepare();

    return $this->render('menu::menu_top.admin.create', $data);
  }

  public function store(MenutopStoreRequest $request)
  {
    $this->repository->create($request->all());
    $this->cacheDelete();

    return $this->repository->redirectToList();
  }

  public function edit($id)
  {
    $data["lists"] = $this->repository->prepare();
    $data['result'] = $this->repository->get($id);

    return $this->render('menu::menu_top.admin.edit', $data);
  }

  public function update(MenutopStoreRequest $request, $id)
  {
    $this->repository->update($request->all(), $id);
    $this->cacheDelete();

    return $this->repository->redirectToList();
  }

  public function destroy($id)
  {
    $this->repository->destroy($id);
    $this->cacheDelete();

    return $this->repository->redirectToList();
  }

  public function sort(Request $request)
  {
    $this->repository->sort($request->all());
    $this->cacheDelete();

    return response()->json(['success' => 'updated successfully.']);
  }

  private function cacheDelete()
  {
    return Cache::forget('menu_top');
  }
}
