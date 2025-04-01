<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Setting\Entities\Aside;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Model::unguard();

    $permissions = [];
    $method_permissions = [
      [
        'alias' => 'list',
        'text' => 'แสดง'
      ],
      [
        'alias' => 'create',
        'text' => 'สร้าง'
      ],
      [
        'alias' => 'edit',
        'text' => 'แก้ไข'
      ],
      [
        'alias' => 'delete',
        'text' => 'ลบ'
      ],
      [
        'alias' => 'show',
        'text' => 'ดู'
      ]
    ];

    $except_permissions = [
      'dashboard' => [
        'create',
        'edit',
        'delete',
        'show'
      ],
      'meta' => [
        'delete',
        'show'
      ],
      'report_dataset' => [
        'delete',
        'show'
      ]
    ];

    $navigations = Aside::getNavigationSidebar(true);
    foreach ($navigations as $index => $navigation) {
      if (isset($navigation['data']) && !empty($navigation['data'])) {
        foreach ($navigation['data'] as $parent) {
          $methods = $method_permissions;
          if (isset($except_permissions[$parent['permission_prefix']])) {
            $methods = [];
            foreach ($method_permissions as $index => $permission) {
              if (false === array_search($permission['alias'], $except_permissions[$parent['permission_prefix']])) {
                $methods[] = $method_permissions[$index];
              }
            }
          }

          foreach ($methods as $method) {
            $item = [
              'name' => $parent['permission_prefix'] . '@*' . $method['alias'],
              'display_name' => $parent['name'] . '@*' . $method['text']
            ];
            // $permissions[$parent['permission_prefix']][] = $item;
            $permissions[] = $item;
          }

          if (isset($parent['childs']) && !empty($parent['childs'])) {
            foreach ($parent['childs'] as $child) {
              $methods = $method_permissions;
              if (isset($except_permissions[$child['permission_prefix']])) {
                $methods = [];
                foreach ($method_permissions as $index => $permission) {
                  if (false === array_search($permission['alias'], $except_permissions[$child['permission_prefix']])) {
                    $methods[] = $method_permissions[$index];
                  }
                }
              }

              foreach ($methods as $method) {
                $item = [
                  'name' => $child['permission_prefix'] . '@*' . $method['alias'],
                  'display_name' => $child['name'] . '@*' . $method['text']
                ];
                // $permissions[$child['permission_prefix']][] = $item;
                $permissions[] = $item;
              }
            }
          }
        }
      }
    }

    foreach ($permissions as $permission) {
      $r = Permission::where('name', $permission['name'])->first();
      if (empty($r)) {
        Permission::create([
          'name' => $permission['name'],
          'display_name' => $permission['display_name'],
          'guard_name' => 'web'
        ]);
      } else {
        Permission::where('id', $r->id)->update([
          'name' => $permission['name'],
          'display_name' => $permission['display_name']
        ]);
      }
    }
  }
}
