<?php

namespace Modules\News\Http\Controllers\Admin;

use App\Http\Controllers\BaseManageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\News\Http\Requests\NewsStoreRequest;
use Modules\News\Repositories\NewsRepository as Repository;
use Modules\News\Repositories\NewsTypeRepository as RepositoryNewsType;

class NewsController extends BaseManageController
{
  protected $repository;
  protected $repositoryNewType;

  public function __construct(Repository $repository, RepositoryNewsType $repositoryNewType)
  {
    $this->repository = $repository;
    $this->repositoryNewType = $repositoryNewType;

    $this->init([
      'body' => [
        'app_title' => [
          'h1' => '<i class="app-menu__icon far fa-newspaper"></i> ข่าวสาร',
          'p' => 'สามารถ เพิ่มและแก้ไขข่าวสารได้'
        ]
      ],
      'permission_prefix' => 'news'
    ]);
  }

  public function index($type = "")
  {
    $data['type_id'] = $type;
    $data['type_lists'] = $this->repositoryNewType->getAll(false);
    $data['lists'] = $this->repository->paginate([
      'type' => $type
    ]);

    return $this->render('news::admin.index', $data);
  }

  public function create()
  {
    $data['types'] = $this->repositoryNewType->getAll(false);

    return $this->render('news::admin.create', $data);
  }

  public function store(NewsStoreRequest $request)
  {
    $this->repository->create($request->all());

    return $this->repository->redirectToList();
  }

  public function edit($id)
  {
    $data['types'] = $this->repositoryNewType->getAll(false);
    $data['result'] = $this->repository->get($id);
    $result = $this->repository->get($id);

    if ($result->cover === 'assets/images/new_thumbnail.jpg') {
      $result->cover_display = 'รูปภาพตั้งต้น (default)';
    } else {
      $result->cover_display = $result->cover;
    }

    // จัดการไฟล์แนบ...
    $initialPreview = [];
    $initialPreviewConfig = [];
    if (!empty($result->attach)) {
      foreach ($result->attach as $index => $value) {
        $initialPreview[$index] = Storage::url('news/' . gen_folder($result->id) . '/attach/' . $value['name_uploaded']);
        $initialPreviewConfig[$index] = [
          'type' => __fileType($value["name"]),
          'caption' => $value["name"],
          'size' => $value["size"],
          'width' => "120px",
          'key' => $index,
          'downloadUrl' => Storage::url('news/' . gen_folder($result->id) . '/attach/' . $value['name_uploaded'])
        ];
      }
    }
    $result->initialPreview = json_encode($initialPreview);
    $result->initialPreviewConfig = json_encode($initialPreviewConfig);

    $data['result'] = $result;

    return $this->render('news::admin.edit', $data);
  }

  public function update(NewsStoreRequest $request, $id)
  {
    $this->repository->update($request->all(), $id);

    return $this->repository->redirectToList();
  }

  public function show($id)
  {
    $data = $this->repository->get($id);

    return $this->render('news::admin.show', $data);
  }

  public function destroy($id)
  {
    $this->repository->destroy($id);

    return $this->repository->redirectToList();
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
    try {
      $index = $request->input("key");
      $result = $this->repository->get($id);
      
      // ตรวจสอบว่ามีข้อมูลและ index ที่ถูกต้อง
      if (!$result || !isset($result->attach) || !isset($result->attach[$index])) {
        return response()->json([
          'error' => 'ไม่พบไฟล์ที่ต้องการลบ'
        ], 404);
      }
      
      $attach = $result->attach;
      
      // ตรวจสอบว่ามีชื่อไฟล์ที่ต้องการลบ
      if (!isset($attach[$index]["name_uploaded"])) {
        return response()->json([
          'error' => 'ข้อมูลไฟล์ไม่ถูกต้อง'
        ], 400);
      }

      // ลบไฟล์จาก storage
      $this->repository->storageAttachDelete($id, $attach[$index]["name_uploaded"]);
      
      // ลบข้อมูลไฟล์จาก array
      unset($attach[$index]);

      // อัพเดทข้อมูลใน database
      $response = array_values($attach);
      $this->repository->update([
        'attach' => $response
      ], $id);

      return response()->json([
        'success' => true, 
        'message' => 'ลบไฟล์สำเร็จ'
      ]);
      
    } catch (\Exception $e) {
      // บันทึก error log
      Log::error('Error deleting attachment: ' . $e->getMessage(), [
        'id' => $id,
        'index' => $request->input("key"),
        'trace' => $e->getTraceAsString()
      ]);
      
      return response()->json([
        'error' => 'เกิดข้อผิดพลาดในการลบไฟล์ กรุณาลองอีกครั้ง'
      ], 500);
    }
  }
}

