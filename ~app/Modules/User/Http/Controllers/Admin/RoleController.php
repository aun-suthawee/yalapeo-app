<?php

namespace Modules\User\Http\Controllers\Admin;

use App\Http\Controllers\BaseManageController;
use Illuminate\Support\Facades\Artisan;
use Modules\User\Http\Requests\RoleStoreRequest;
use Modules\User\Repositories\RoleRepository as Repository;

use Spatie\Permission\Models\Permission;

class RoleController extends BaseManageController
{
  protected $repository;

  public function __construct(Repository $repository)
  {
    $this->repository = $repository;

    $this->init([
      'body' => [
        'app_title' => [
          'h1' => '<i class="fas fa-layer-group"></i> บทบาท',
          'p' => 'สามารถ เพิ่มและแก้ไขข้อมูลบทบาทได้',
        ],
      ],
      'breadcrumb' => [
        'class_name' => 'RoleBreadcrumb'
      ],
      'permission_prefix' => 'role'
    ]);
  }

  public function index()
  {
    $data['lists'] = $this->repository->paginate();

    return $this->render('user::admin.role.index', $data);
  }

  private function getPermissions()
  {
    $response = [];
    $permissions = Permission::all()->toArray();
    foreach ($permissions as $index => $permission) {
      [$prefix, $method] = explode("@*", $permission['display_name']);

      $response[$prefix]['prefix'] = $prefix;
      $response[$prefix]['method'][$permission['id']]['id'] = $permission['id'];
      $response[$prefix]['method'][$permission['id']]['name'] = $method;
    }

    return $response;
  }

  public function create()
  {
    $data['permissions'] = $this->getPermissions();

    return $this->render('user::admin.role.create', $data);
  }

  public function store(RoleStoreRequest $request)
  {
    $role = $this->repository->create($request->only(['name', 'display_name']));
    $role->syncPermissions($request->input('permission'));

    return $this->repository->redirectToList();
  }

  public function edit($id)
  {
    $data['result'] = $this->repository->get($id);
    $data['role_permission'] = $data['result']->permissions->toArray();

    $data['permissions'] = $this->getPermissions();

    return $this->render('user::admin.role.edit', $data);
  }

  public function update(RoleStoreRequest $request, $id)
  {
    $role = $this->repository->update($request->only(['name', 'display_name']), $id);
    $role->syncPermissions($request->input('permission'));

    return $this->repository->redirectToList();
  }

  public function destroy($id)
  {
    $this->repository->destroy($id);

    return $this->repository->redirectToList();
  }

  public function sync()
  {
    Artisan::call('permission:cache-reset');
    Artisan::call('module:seed --class=PermissionTableSeeder User');

    return $this->repository->redirectToList();
  }
}
