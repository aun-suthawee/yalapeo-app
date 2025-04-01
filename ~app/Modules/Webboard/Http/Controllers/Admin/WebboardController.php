<?php

namespace Modules\Webboard\Http\Controllers\Admin;

use App\Http\Controllers\BaseManageController;
use Illuminate\Http\Request;
use Modules\Webboard\Repositories\WebboardRepository as Repository;

class WebboardController extends BaseManageController
{
  protected $repository;

  public function __construct(Repository $repository)
  {
    $this->repository = $repository;

    $this->init([
      'body' => [
        'app_title' => [
          'h1' => '<i class="fas fa-comments"></i> เว็บบอร์ด',
          'p' => 'สามารถ เพิ่มและแก้ไขข้อมูลเว็บบอร์ดได้'
        ]
      ],
      'permission_prefix' => 'webboard'
    ]);
  }

  public function index()
  {
    $data['lists'] = $this->repository->paginate([]);
    return $this->render('webboard::admin.index', $data);
  }

  public function create()
  {
    return $this->render('webboard::admin.create');
  }

  public function store(Request $request)
  {
    $this->repository->create($request->all());

    return $this->repository->redirectToList();
  }

  public function edit($id)
  {
    $data['result'] = $this->repository->get($id);

    return $this->render('webboard::admin.edit', $data);
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

  public function show($id)
  {
    $data['result'] = $this->repository->get($id);

    return $this->render('webboard::admin.show', $data);
  }
}
