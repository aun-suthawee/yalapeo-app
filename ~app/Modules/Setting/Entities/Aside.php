<?php

namespace Modules\Setting\Entities;

use Auth;

class Aside
{
  public static function setNavigationSidebar(): array
  {
    return [
      [
        'role' => [
          'name' => 'MAIN NAVIGATION'
        ],
        'data' => [
          [
            'name' => 'Dashboard',
            'active_name' => "admin/dashboard",
            'url' => route('admin.dashboard.index'),
            'permission_prefix' => "dashboard",
            'webfont_icon' => '<i class="app-menu__icon fas fa-tachometer-alt"></i>'
          ],
          [
            'name' => 'ข่าวสาร',
            'active_name' => "admin/news*",
            'url' => '',
            'permission_prefix' => "news",
            'webfont_icon' => '<i class="app-menu__icon far fa-newspaper"></i>',
            'childs' => [
              [
                'name' => 'ข่าวสาร',
                'active_name' => "admin/news**",
                'url' => route('admin.news.index'),
                'permission_prefix' => "news",
                'webfont_icon' => '<i class="app-menu__icon far fa-circle"></i>'
              ],
              [
                'name' => 'ประเภทข่าวสาร',
                'active_name' => "admin/news/type**",
                'url' => route('admin.news.type.index'),
                'permission_prefix' => "news_type",
                'webfont_icon' => '<i class="app-menu__icon far fa-circle"></i>'
              ],
            ]
          ],
          [
            'name' => 'ภาพสไลด์',
            'active_name' => "admin/banner*",
            'url' => '',
            'permission_prefix' => "banner_large",
            'webfont_icon' => '<i class="app-menu__icon fab fa-slideshare"></i>',
            'childs' => [
              [
                'name' => 'ภาพสไลด์ (L)',
                'active_name' => "admin/banner/large**",
                'url' => route('admin.banner.large.index'),
                'permission_prefix' => "banner_large",
                'webfont_icon' => '<i class="app-menu__icon far fa-circle"></i>'
              ],

            ]
          ],
          [
            'name' => 'รูปภาพสไลด์สี่เหลี่ยม',
            'active_name' => "admin/imageboxslider*",
            'url' => route('admin.imageboxslider.index'),
            'permission_prefix' => "imageboxslider",
            'webfont_icon' => '<i class="app-menu__icon fas fa-images"></i>'
          ],
          [
            'name' => ' Page',
            'active_name' => "admin/page*",
            'url' => route('admin.page.index'),
            'permission_prefix' => "page",
            'webfont_icon' => '<i class="app-menu__icon fas fa-file-alt"></i>'
          ],
          [
            'name' => 'หนังสือ e-Book',
            'active_name' => "admin/book*",
            'url' => route('admin.book.index'),
            'permission_prefix' => "book",
            'webfont_icon' => '<i class="app-menu__icon fas fa-bible"></i>'
          ],
          [
            'name' => 'กิจกรรม',
            'active_name' => "admin/gallery*",
            'url' => route('admin.gallery.index'),
            'permission_prefix' => "gallery",
            'webfont_icon' => '<i class="app-menu__icon fas fa-images"></i>'
          ],
          [
            'name' => 'เว็บบอร์ด',
            'active_name' => "admin/webboard*",
            'url' => route('admin.webboard.index'),
            'permission_prefix' => "webboard",
            'webfont_icon' => '<i class="app-menu__icon fas fa-comments"></i>'
          ],
          [
            'name' => 'ทำเนียบบุคลากร',
            'active_name' => "admin/personal*",
            'url' => route('admin.personal.index'),
            'permission_prefix' => "personal",
            'webfont_icon' => '<i class="app-menu__icon fas fa-sitemap"></i>'
          ],
          [
            'name' => 'ITA',
            'active_name' => "admin/ita*",
            'url' => route('admin.ita.index'),
            'permission_prefix' => "ita",
            'webfont_icon' => '<i class="app-menu__icon fas fa-spell-check"></i>'
          ],
          [
            'name' => 'LPA',
            'active_name' => "admin/lpa*",
            'url' => route('admin.lpa.index'),
            'permission_prefix' => "lpa",
            'webfont_icon' => '<i class="app-menu__icon fas fa-spell-check"></i>'
          ],
          [
            'name' => 'Box',
            'active_name' => "admin/box**",
            'url' => route('admin.box.index'),
            'permission_prefix' => "box",
            'webfont_icon' => '<i class="app-menu__icon fas fa-dice-d6"></i>'
          ],
          [
            'name' => 'วิดิโอ',
            'active_name' => "admin/video*",
            'url' => route('admin.video.index'),
            'permission_prefix' => "video",
            'webfont_icon' => '<i class="app-menu__icon fab fa-youtube"></i>'
          ],
          [
            'name' => 'วิดีโอ TikTok',
            'active_name' => "admin/tiktokvideo*",
            'url' => route('admin.tiktokvideo.index'),
            'permission_prefix' => "tiktokvideo",
            'webfont_icon' => '<i class="app-menu__icon fab fa-tiktok"></i>'
          ],
        ]
      ],
      [
        'role' => [
          'name' => 'SYSTEMS SETTING'
        ],
        'data' => [
          [
            'name' => 'เมนูด้านบน',
            'active_name' => "admin/menu-top*",
            'url' => route('admin.menu-top.index'),
            'permission_prefix' => "menu_top",
            'webfont_icon' => '<i class="app-menu__icon fab fa-monero"></i>'
          ],
          [
            'name' => 'เมนูด้านข้าง',
            'active_name' => "admin/menu-side*",
            'url' => route('admin.menu-side.index'),
            'permission_prefix' => "menu_side",
            'webfont_icon' => '<i class="app-menu__icon fab fa-monero"></i>'
          ],
          [
            'name' => 'ผู้ใช้งาน',
            'active_name' => "admin/user*",
            'url' => '',
            'permission_prefix' => "user",
            'webfont_icon' => '<i class="app-menu__icon fas fa-users-cog"></i>',
            'childs' => [
              [
                'name' => 'บทบาท',
                'active_name' => "admin/user/role**",
                'url' => route('admin.user.role.index'),
                'permission_prefix' => "role",
                'webfont_icon' => '<i class="app-menu__icon far fa-circle"></i>'
              ],
              [
                'name' => 'ผู้ใช้งาน',
                'active_name' => "admin/user**",
                'url' => route('admin.user.index'),
                'permission_prefix' => "user",
                'webfont_icon' => '<i class="app-menu__icon far fa-circle"></i>'
              ]
            ]
          ],
          [
            'name' => 'คำอธิบายเว็บไซต์',
            'active_name' => "admin/setting/meta*",
            'url' => route('admin.setting.meta.index'),
            'permission_prefix' => "meta",
            'webfont_icon' => '<i class="app-menu__icon fas fa-text-width"></i>'
          ]
        ]
      ]
    ];
  }

  /**
   * @param bool $expanded
   *
   * @return array
   */
  public static function getNavigationSidebar(bool $expanded = false): array
  {
    $response = [];
    foreach (self::setNavigationSidebar() as $role_index => $role) {
      if (!$expanded) {
        if (isset($role['data']) && !empty($role['data'])) {
          foreach ($role['data'] as $parent_index => $parent) {
            if (false !== array_search($parent['permission_prefix'], self::getPermissionAuth())) {
              $role['data'][$parent_index] = $parent;

              if (isset($parent['childs']) && !empty($parent['childs'])) {
                foreach ($parent['childs'] as $child_index => $child) {
                  if (false == array_search($child['permission_prefix'], self::getPermissionAuth())) {
                    unset($role['data'][$parent_index]['childs'][$child_index]);
                  }
                }
              }
            } else {
              unset($role['data'][$parent_index]);
            }
          }
        }
      }

      $response[$role_index] = $role;
    }

    return $response;
  }

  public static function getPermissionAuth(): array
  {
    $response = [];
    $user = Auth::user();
    $permissions = $user->getAllPermissions()->toArray();
    foreach ($permissions as $permission) {
      [$prefix, $method] = explode("@*", $permission['name']);
      $response[$prefix] = $prefix;
    }

    return array_values($response);
  }
}

