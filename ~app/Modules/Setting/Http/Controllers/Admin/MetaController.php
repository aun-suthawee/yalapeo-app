<?php

namespace Modules\Setting\Http\Controllers\Admin;

use App\Http\Controllers\BaseManageController;
use Illuminate\Http\Request;
use Modules\Setting\Repositories\MetaRepository as Repository;

class MetaController extends BaseManageController
{
  protected $repository;

  public function __construct(Repository $repository)
  {
    $this->repository = $repository;

    $this->init([
      'body' => [
        'app_title' => [
          'h1' => '<i class="fas fa-text-width"></i> คำอธิบายเว็บไซต์',
          'p'  => 'สามารถ เพิ่มและแก้ไขข้อมูลคำอธิบายเว็บไซต์ได้'
        ],
      ],
      'permission_prefix' => 'meta'
    ]);
  }

  public function index()
  {
    $data['result'] = $this->repository->get(1);

    return $this->render('setting::meta.index', $data);
  }

  public function update(Request $request, $id)
  {
    $this->repository->update($request->all(), $id);
    \Cache::forget('cacheMeta');

    return $this->repository->redirectToList();
  }
}
