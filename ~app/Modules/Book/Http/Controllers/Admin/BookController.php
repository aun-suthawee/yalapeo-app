<?php

namespace Modules\Book\Http\Controllers\Admin;

use App\Http\Controllers\BaseManageController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Book\Http\Requests\BookStoreRequest;
use Modules\Book\Repositories\BookRepository as Repository;

class BookController extends BaseManageController
{
  protected $repository;

  public function __construct(Repository $repository)
  {
    $this->repository = $repository;

    $this->init([
      'body' => [
        'app_title' => [
          'h1' => '<i class="fas fa-bible"></i> ข้อมูลหนังสือ',
          'p' => 'สามารถ เพิ่มและแก้ไขข้อมูลหนังสือ ได้',
        ],
      ],
      'permission_prefix' => 'book'
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

    return $this->render('book::admin.index', $data);
  }

  public function create()
  {
    return $this->render('book::admin.create');
  }

  public function store(BookStoreRequest $request): RedirectResponse
  {
    $this->repository->create($request->all());

    return $this->repository->redirectToList();
  }

  public function edit($id)
  {
    $data['result'] = $this->repository->get($id);

    return $this->render('book::admin.edit', $data);
  }

  public function update(BookStoreRequest $request, $id): RedirectResponse
  {
    $this->repository->update($request->all(), $id);

    return $this->repository->redirectToList();
  }

  public function destroy($id): RedirectResponse
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
