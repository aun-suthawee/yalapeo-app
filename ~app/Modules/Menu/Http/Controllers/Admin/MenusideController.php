<?php

namespace Modules\Menu\Http\Controllers\Admin;

use App\Http\Controllers\BaseManageController;
use Illuminate\Http\Request;
use Modules\Menu\Http\Requests\MenusideStoreRequest;
use Modules\Menu\Repositories\MenusideRepository as Repository;
use Cache;
use Storage;

class MenusideController extends BaseManageController
{
  protected $repository;

  public function __construct(Repository $repository)
  {
    $this->repository = $repository;

    $this->init([
      'body' => [
        'app_title' => [
          'h1' => '<i class="fab fa-monero"></i> เมนูด้านข้าง',
          'p' => 'สามารถ เพิ่มและแก้ไขข้อมูลเมนูด้านข้างได้',
        ],
      ],
      'breadcrumb' => [
        'class_name' => 'MenusideBreadcrumb'
      ],
      'permission_prefix' => 'menu_side'
    ]);
  }

  public function index()
  {
    $data["lists"] = $this->repository->prepare();
    $data['scripts'] = [
      asset('assets/@site_control/js/jui-sortable.min.js')
    ];

    return $this->render('menu::menu_side.admin.index', $data);
  }

  public function create()
  {
    $data["lists"] = $this->repository->prepare();

    return $this->render('menu::menu_side.admin.create', $data);
  }

  public function store(MenusideStoreRequest $request)
  {
    $this->repository->create($request->all());
    $this->cacheDelete();

    return $this->repository->redirectToList();
  }

  public function edit($id)
  {
    $data["lists"] = $this->repository->prepare();
    $result = $this->repository->get($id);

    $initialPreview = [];
    $initialPreviewConfig = [];
    if (!empty($result->attach)) {
      foreach ($result->attach as $index => $value) {
        $initialPreview[$index] = Storage::url('menuside/' . gen_folder($result->id) . '/attach/' . $value['name_uploaded']);
        $initialPreviewConfig[$index] = [
          'type' => __fileType($value["name"]),
          'caption' => $value["name"],
          'size' => $value["size"],
          'width' => "120px",
          'key' => $index,
          'downloadUrl' => Storage::url('menuside/' . gen_folder($result->id) . '/attach/' . $value['name_uploaded'])
        ];
      }
    }
    $result->initialPreview = json_encode($initialPreview);
    $result->initialPreviewConfig = json_encode($initialPreviewConfig);

    $data['result'] = $result;

    return $this->render('menu::menu_side.admin.edit', $data);
  }

  public function update(MenusideStoreRequest $request, $id)
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
    return Cache::forget('menu_side');
  }

  public function attachSort(Request $request, $id)
  {
    $result = $this->repository->get($id);
    $attach = $result->attach;

    $response = [];
    foreach ($request->input("stack") as $index => $value) {
      $response[$index] = $attach[$value['key']];
    }

    $this->repository->update([
      'attach' => $response
    ], $id);

    return response()->json(['success' => 'updated successfully.']);
  }

  public function attachDestroy(Request $request, $id)
  {
    $index = $request->input("key");
    $result = $this->repository->get($id);
    $attach = $result->attach;

    $this->repository->storageAttachDelete($id, $attach[$index]["name_uploaded"]);
    unset($attach[$index]);

    $response = array_values($attach);
    $this->repository->update([
      'attach' => $response
    ], $id);

    return response()->json(['success' => 'updated successfully.']);
  }
}
