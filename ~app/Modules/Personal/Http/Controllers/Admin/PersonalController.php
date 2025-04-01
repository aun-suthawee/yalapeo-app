<?php

namespace Modules\Personal\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseManageController;
use Modules\Personal\Repositories\PersonalRepository as Repository;

class PersonalController extends BaseManageController
{
  protected $repository;

  public function __construct(Repository $repository)
  {
    $this->repository = $repository;

    $this->init([
      'body' => [
        'app_title' => [
          'h1' => '<i class="fas fa-sitemap"></i> ทำเนียบบุคลากร',
          'p' => 'สามารถ เพิ่มและแก้ไขข้อมูลทำเนียบบุคลากรได้'
        ]
      ],
      'permission_prefix' => 'personal'
    ]);
  }

  public function index()
  {
    $data["lists"] = $this->repository->prepare();
    $data['scripts'] = [
      asset('assets/@site_control/js/jui-sortable.min.js')
    ];

    return $this->render('personal::admin.index', $data);
  }

  public function create()
  {
    $data["lists"] = $this->repository->prepare();

    return $this->render('personal::admin.create', $data);
  }

  public function store(Request $request)
  {
    $this->repository->create($request->all());

    return $this->repository->redirectToList();
  }

  public function edit($id)
  {
    $data["lists"] = $this->repository->prepare();
    $data['result'] = $this->repository->get($id);

    return $this->render('personal::admin.edit', $data);
  }

  public function update(Request $request, $id)
  {
    $this->repository->update($request->all(), $id);

    return $this->repository->redirectToList();
  }

  public function destroy($id)
  {
    $this->repository->destroy($id);

    return $this->repository->redirectToList();
  }

  public function sort(Request $request)
  {
    $this->repository->sort($request->all());

    return response()->json(['success' => 'updated successfully.']);
  }
}
