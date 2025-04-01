<?php


namespace Modules\Account\Breadcrumb\Admin;


class AccountBreadcrumb
{
	public static function index()
	{
		return [
			[
				'label' => 'Dashboard',
				'link' => route('admin.dashboard.index')
			],
			[
				'label' => 'โปรไฟล์',
				'link' => ''
			]
		];
	}

	public static function password()
	{
		return [
			[
				'label' => 'Dashboard',
				'link' => route('admin.dashboard.index')
			],
			[
				'label' => 'เปลี่ยนรหัสผ่าน',
				'link' => ''
			]
		];
	}
}