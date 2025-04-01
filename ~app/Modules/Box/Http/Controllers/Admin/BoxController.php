<?php

namespace Modules\Box\Http\Controllers\Admin;

use App\Http\Controllers\BaseManageController;
use Illuminate\Http\Request;
use Modules\Box\Repositories\BoxRepository as Repository;

class BoxController extends BaseManageController
{
  protected $repository;

  public function __construct(Repository $repository)
  {
    $this->repository = $repository;

    $this->init([
      'body' => [
        'app_title' => [
          'h1' => '<i class="fas fa-file-alt"></i> ข้อมูล Box',
          'p' => 'สามารถ เพิ่มและแก้ไขข้อมูล Box ได้',
        ],
      ],
      'permission_prefix' => 'box'
    ]);
  }

  public function index()
  {
    $data['result'] = $this->repository->get(1);

    return $this->render('box::admin.index', $data);
  }

  public function update(Request $request, $id)
  {

    $this->repository->update($request->all(), $id);

    return $this->repository->redirectToList();
  }
}
