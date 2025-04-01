<?php

namespace Modules\Gallery\Http\Controllers\Admin;

use Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseManageController;
use Modules\Gallery\Http\Requests\GalleryStoreRequest;
use Modules\Gallery\Repositories\GalleryRepository as Repository;

class GalleryController extends BaseManageController
{
  protected $repository;

  public function __construct(Repository $repository)
  {
    $this->repository = $repository;

    $this->init([
      'body' => [
        'app_title' => [
          'h1' => '<i class="fas fa-images"></i> กิจกรรม',
          'p' => 'สามารถ เพิ่มและแก้ไขข้อมูลกิจกรรมได้'
        ]
      ],
      'permission_prefix' => 'gallery'
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

    return $this->render('gallery::admin.index', $data);
  }

  public function create()
  {
    return $this->render('gallery::admin.create');
  }

  public function store(GalleryStoreRequest $request)
  {
    $this->repository->create($request->all());

    return $this->repository->redirectToList();
  }

  public function edit($id)
  {
    $result = $this->repository->get($id);

    $initialPreview = [];
    $initialPreviewConfig = [];
    if (!empty($result->slider)) {
      foreach ($result->slider as $index => $value) {
        $initialPreview[$index] = Storage::url('gallery/' . gen_folder($result->id) . '/' . $value['name_uploaded']);
        $initialPreviewConfig[$index] = [
          'caption' => $value["name"],
          'size' => $value["size"],
          'width' => "120px",
          'key' => $index,
          'downloadUrl' => Storage::url('gallery/' . gen_folder($result->id) . '/' . $value['name_uploaded'])
        ];
      }
    }
    $result->initialPreview = _jsonUnescapedUnicode($initialPreview);
    $result->initialPreviewConfig = _jsonUnescapedUnicode($initialPreviewConfig);

    $data['result'] = $result;

    return $this->render('gallery::admin.edit', $data);
  }

  public function update(GalleryStoreRequest $request, $id)
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

  public function sliderSort(Request $request, $id)
  {
    $result = $this->repository->get($id);
    $slider = $result->slider;

    $response = [];
    foreach ($request->input("stack") as $index => $value) {
      $response[$index] = $slider[$value['key']];
    }

    $this->repository->update([
      'slider' => _jsonUnescapedUnicode($response)
    ], $id);

    return response()->json(['success' => 'updated successfully.']);
  }

  public function sliderDestroy(Request $request, $id)
  {
    $index = $request->input("key");
    $result = $this->repository->get($id);
    $slider = $result->slider;

    $this->repository->storageSliderDelete($id, $slider[$index]["name_uploaded"]);
    unset($slider[$index]);

    $response = array_values($slider);
    $this->repository->update([
      'slider' => _jsonUnescapedUnicode($response)
    ], $id);

    return response()->json(['success' => 'updated successfully.']);
  }
}
