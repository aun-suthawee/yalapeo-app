<?php

namespace Modules\Personal\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseManageController;
use Modules\Personal\Repositories\PersonalRepository;
use Modules\Personal\Repositories\TreeRepository as Repository;

class TreeController extends BaseManageController
{
  protected $repository;
  protected $personalRepository;

  public function __construct(
    Repository $repository,
    PersonalRepository $personalRepository
  ) {
    $this->repository = $repository;
    $this->personalRepository = $personalRepository;

    $this->init([
      'body' => [
        'app_title' => [
          'h1' => '<i class="fas fa-sitemap"></i> ทำเนียบบุคลากร',
          'p' => 'สามารถ เพิ่มและแก้ไขข้อมูลทำเนียบบุคลากรได้'
        ]
      ],
      'breadcrumb' => [
        'class_name' => 'TreeBreadcrumb'
      ],
      'permission_prefix' => 'personal'
    ]);
  }

  public function index($personal_id)
  {
    $data['personal'] = $this->personalRepository->get($personal_id);
    $data['lists'] = $this->repository->paginate([
      'personal_id' => $personal_id
    ], [
      'sequent_row' => 'ASC',
      'sequent_col' => 'ASC',
    ]);

    return $this->render('personal::admin.tree.index', $data);
  }

  public function store(Request $request, $personal_id)
  {
    $this->repository->create($request->all() + [
      'personal_id' => $personal_id
    ]);

    return redirect()->back()->withInput();
  }

  public function edit($personal_id, $id)
  {
    $data['personal'] = $this->personalRepository->get($personal_id);
    $data['result'] = $this->repository->get($id);
    $data['lists'] = $this->repository->paginate([
      'personal_id' => $personal_id
    ]);

    return $this->render('personal::admin.tree.index', $data);
  }

  public function update(Request $request, $personal_id, $id)
  {
    $this->repository->update($request->all(), $id);

    return redirect()->route('admin.personal.struct.index', $personal_id);
  }

  public function destroy($personal_id, $id)
  {
    $this->repository->destroy($id);

    return redirect()->back()->withInput();
  }
}
