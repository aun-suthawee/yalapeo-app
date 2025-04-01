<?php

namespace Modules\Webboard\Http\Controllers;

use App\Http\Controllers\BaseViewController;
use Modules\Webboard\Http\Requests\AnswerStoreRequest;
use Modules\Webboard\Repositories\AnswerRepository as Repository;

class AnswerController extends BaseViewController
{
  protected $repository;

  public function __construct(Repository $repository)
  {
    $this->repository = $repository;

    $this->init([
      'body' => [
        'title' => 'กระทู้ทั้งหมด',
        'description'  => 'กระทู้ทั้งหมด'
      ]
    ]);
  }


  public function index()
  {
    dd(\Request::route()->getName());
    return view('webboard::index');
  }

  public function store(AnswerStoreRequest $request, $board_id)
  {
    $this->repository->create($request->all() + [
      'webboard_id' => $board_id
    ]);

    return redirect()->back()->withInput();
  }
}
