<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSupperadminTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Model::unguard();

    return \DB::transaction(function () {

      $role = Role::create([
        'name' => 'supperadmin',
        'display_name' => 'ผู้ดูแลระบบ',
      ]);

      $response = [];
      $permissions = Permission::all()->toArray();
      foreach ($permissions as $index => $permission) {
        $response[] = $permission['name'];
      }

      $role->syncPermissions($response);


      $user = User::findOrFail(2);
      $user->syncRoles($role->name);
    });
  }
}
