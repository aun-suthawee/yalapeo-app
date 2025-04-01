<?php

namespace Modules\Assessment\Http\Controllers\Admin;

use App\Http\Controllers\BaseManageController;
use Illuminate\Http\Request;
use Modules\Assessment\Http\Requests\LpaStoreRequest;
use Modules\Assessment\Repositories\LpaRepository as Repository;

class LpaController extends BaseManageController
{
  protected $repository;

  public function __construct(Repository $repository)
  {
    $this->repository = $repository;

    $this->init([
      'body' => [
        'app_title' => [
          'h1' => '<i class="fas fa-spell-check"></i>  LPA',
          'p' => 'สามารถ เพิ่มและแก้ไขข้อมูล LPA ได้',
        ],
      ],
      'permission_prefix' => 'lpa',
      'breadcrumb' => [
        'class_name' => 'LpaBreadcrumb'
      ],
    ]);
  }

  public function index(Request $request)
  {
    $data['request_year'] = $request->y ??  YEAR('th');
    $data['years'] = $this->repository->lpasDistinctYear();
    $data['result'] = $this->repository->lpasOfYear($data['request_year']);

    return $this->render('assessment::lpa.admin.index', $data);
  }

  public function create()
  {
    return $this->render('assessment::lpa.admin.create');
  }

  public function store(LpaStoreRequest $request)
  {
    $lpas = json_decode(file_get_contents($request->url));
    $this->repository->create($request->all() + ['lpas' => $lpas]);

    return $this->repository->redirectToList();
  }

  public function edit(Request $request, $id)
  {
    $data['result'] = $this->repository->get($id);
    $data['lpas'] = $data['result']['lpas'][$request->i];

    return $this->render('assessment::lpa.admin.edit', $data);
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
