<?php

namespace Modules\User\Http\Controllers\Admin;

use App\Http\Controllers\BaseManageController;
use Modules\User\Http\Requests\ChangePasswordRequest;
use Modules\User\Repositories\UserRepository as Repository;
use Hash;

class ChangePasswordController extends BaseManageController
{
  protected $repository;

  public function __construct(Repository $repository)
  {
    $this->repository = $repository;

    $this->init([
      'body' => [
        'app_title' => [
          'h1' => '<i class="fas fa-users-cog"></i> ผู้ใช้งาน',
          'p' => 'สามารถ เพิ่มและแก้ไขและลบข้อมูลผู้ใช้งานระบบได้',
        ],
      ],
      'permission_prefix' => 'user'
    ]);
  }

  public function edit($id)
  {
    $data['user'] = $this->repository->get($id);

    return $this->render('user::admin.changepassword.index', $data);
  }

  public function update(ChangePasswordRequest $request, $id)
  {
    $model = $this->repository->get($id);
    $model->password = $request->password;
    $model->save();

    return $this->repository->redirectToList();
  }
}
