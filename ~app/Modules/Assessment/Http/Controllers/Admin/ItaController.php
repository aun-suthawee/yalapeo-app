<?php

namespace Modules\Assessment\Http\Controllers\Admin;

use App\Http\Controllers\BaseManageController;
use Illuminate\Http\Request;
use Modules\Assessment\Http\Requests\ItaStoreRequest;
use Modules\Assessment\Repositories\ItaRepository as Repository;

class ItaController extends BaseManageController
{
  protected $repository;

  public function __construct(Repository $repository)
  {
    $this->repository = $repository;

    $this->init([
      'body' => [
        'app_title' => [
          'h1' => '<i class="fas fa-spell-check"></i>  ITA',
          'p' => 'สามารถ เพิ่มและแก้ไขข้อมูล ITA ได้',
        ],
      ],
      'permission_prefix' => 'ita',
      'breadcrumb' => [
        'class_name' => 'ItaBreadcrumb'
      ],
    ]);
  }

  public function index(Request $request)
  {
    $data['request_year'] = $request->y ??  YEAR('th');
    $data['years'] = $this->repository->itasDistinctYear();
    $data['result'] = $this->repository->itasOfYear($data['request_year']);

    return $this->render('assessment::ita.admin.index', $data);
  }

  public function create()
  {
    return $this->render('assessment::ita.admin.create');
  }

  public function store(ItaStoreRequest $request)
  {
    $itas = json_decode(file_get_contents($request->url));
    $this->repository->create($request->all() + ['itas' => $itas]);

    return $this->repository->redirectToList();
  }

  public function edit(Request $request, $id)
  {
    $data['result'] = $this->repository->get($id);
    $data['itas'] = $data['result']['itas'][$request->i]['topics'][$request->t];

    return $this->render('assessment::ita.admin.edit', $data);
  }

  public function update(Request $request, $id)
  {
    $result = $this->repository->get($id)->toArray();
    $result['itas'][$request->i]['topics'][$request->t]['url'] = $request->url;
    $result['itas'][$request->i]['topics'][$request->t]['description'] = $request->description;

    $this->repository->update($result, $id);

    return redirect()->to(route('admin.ita.index') . "?y=" . $result['year']);
  }
}
