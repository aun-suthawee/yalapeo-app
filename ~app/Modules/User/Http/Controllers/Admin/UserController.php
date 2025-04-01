<?php

namespace Modules\User\Http\Controllers\Admin;

use App\Http\Controllers\BaseManageController;
use DB;
use Hash;
use Modules\User\Http\Requests\UserStoreRequese;
use Modules\User\Repositories\UserRepository as Repository;
use Spatie\Permission\Models\Role;

class UserController extends BaseManageController
{

  protected $repository;

  public function __construct(Repository $repository)
  {
    $this->repository = $repository;

    $this->init([
      'body' => [
        'app_title' => [
          'h1' => '<i class="fas fa-users-cog"></i> ผู้ใช้งาน',
          'p' => 'สามารถ เพิ่มและแก้ไขข้อมูลผู้ใช้งานได้',
        ],
      ],
      'permission_prefix' => 'user'
    ]);
  }

  public function index()
  {
    $data['lists'] = $this->repository->paginate();

    return $this->render('user::admin.index', $data);
  }

  public function create()
  {
    $data['provinces'] = $this->getProvinces();
    $data['roles'] = Role::all();

    return $this->render('user::admin.create', $data);
  }

  public function store(UserStoreRequese $request)
  {
    $user = $this->repository->create($request->all());
    $user->assignRole($request->input('roles'));

    return $this->repository->redirectToList();
  }

  public function edit($id)
  {
    $data['result'] = $this->repository->get($id);
    $data['user_roles'] = $data['result']->roles->toArray();

    $data['provinces'] = $this->getProvinces();
    $data['roles'] = Role::all();

    return $this->render('user::admin.edit', $data);
  }

  public function update(UserStoreRequese $request, $id)
  {
    $user = $this->repository->update($request->all(), $id);
    DB::table('model_has_roles')->where('model_id', $id)->delete();
    $user->assignRole($request->input('roles'));

    return $this->repository->redirectToList();
  }

  public function destroy($id)
  {
    $this->repository->destroy($id);

    return $this->repository->redirectToList();
  }
}
