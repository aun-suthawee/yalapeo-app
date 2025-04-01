<?php

namespace Modules\Video\Http\Controllers\Admin;

use App\Http\Controllers\BaseManageController;
use Illuminate\Http\Request;
use Modules\Video\Http\Requests\VideoStoreRequest;
use Modules\Video\Repositories\VideoRepository as Repository;

class VideoController extends BaseManageController
{
  protected $repository;

  public function __construct(Repository $repository)
  {
    $this->repository = $repository;

    $this->init([
      'body' => [
        'app_title' => [
          'h1' => '<i class="fab fa-youtube"></i> วีดิโอ',
          'p' => 'สามารถ เพิ่มและแก้ไขข้อมูลวีดิโอได้',
        ],
      ],
      'permission_prefix' => 'video'
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

    return $this->render('video::admin.index', $data);
  }

  public function create()
  {
    return $this->render('video::admin.create');
  }

  public function store(VideoStoreRequest $request)
  {
    $request->request->add(['output' => $request->url]);
    $this->repository->create($request->all());

    return $this->repository->redirectToList();
  }

  public function edit($id)
  {
    $data['result'] = $this->repository->get($id);

    return $this->render('video::admin.edit', $data);
  }

  public function update(VideoStoreRequest $request, $id)
  {
    $request->request->add(['output' => $request->url]);
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
