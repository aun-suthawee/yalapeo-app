<?php

namespace Modules\News\Http\Controllers\Admin;

use App\Http\Controllers\BaseManageController;
use Illuminate\Http\Request;
use Modules\News\Repositories\NewsTypeRepository as Repository;
use Modules\News\Http\Requests\NewsTypeStoreRequest;

class NewsTypeController extends BaseManageController
{
  protected $repository;

  public function __construct(Repository $repository)
  {
    $this->repository = $repository;

    $this->init([
      'body' => [
        'app_title' => [
          'h1' => '<i class="app-menu__icon fab fa-buffer"></i> ข้อมูลประเภทข่าวสาร',
          'p' => 'สามารถ เพิ่มและแก้ไขข้อมูลประเภทข่าวสารได้',
        ],
      ],
      'breadcrumb' => [
        'class_name' => 'NewsTypeBreadcrumb'
      ],
      'permission_prefix' => 'news_type'
    ]);
  }

  public function index()
  {
    $data['lists'] = $this->repository->paginate([], [
      'sort' => 'ASC',
      'id' => 'DESC',
    ]);
    $data['scripts'] = [
      asset('assets/@site_control/js/jui-sortable.min.js')
    ];

    return $this->render('news::admin.types.index', $data);
  }

  public function create()
  {
    return $this->render('news::admin.types.create');
  }

  public function store(NewsTypeStoreRequest $request)
  {
    $this->repository->create($request->all());

    return $this->repository->redirectToList();
  }

  public function edit($id)
  {
    $data['result'] = $this->repository->get($id);

    return $this->render('news::admin.types.edit', $data);
  }

  public function update(NewsTypeStoreRequest $request, $id)
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
