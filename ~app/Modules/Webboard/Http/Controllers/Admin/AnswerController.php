<?php

namespace Modules\Webboard\Http\Controllers\Admin;

use App\Http\Controllers\BaseManageController;
use Modules\Webboard\Http\Requests\AnswerStoreRequest;
use Modules\Webboard\Repositories\AnswerRepository as Repository;

class AnswerController extends BaseManageController
{
  protected $repository;

  public function __construct(Repository $repository)
  {
    $this->repository = $repository;

    $this->init([
      'body' => [
        'app_title' => [
          'h1' => '<i class="fas fa-comments"></i> เว็บบอร์ด',
          'p' => 'สามารถ เพิ่มและแก้ไขข้อมูลเว็บบอร์ดได้'
        ]
      ],
      'permission_prefix' => 'webboard'
    ]);
  }

  public function create()
  {
    return $this->render('answer::admin.create');
  }

  public function store(AnswerStoreRequest $request, $board_id)
  {
    $this->repository->create($request->all() + [
      'webboard_id' => $board_id
    ]);

    return $this->repository->redirectToList();
  }

  public function destroy($board_id, $id)
  {
    $this->repository->destroy($id);

    return $this->repository->redirectToList();
  }
}
