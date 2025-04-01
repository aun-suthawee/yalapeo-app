<?php

namespace Modules\Video\Http\Controllers;

use App\Http\Controllers\BaseViewController;
use Illuminate\Http\Request;
use Modules\Video\Repositories\VideoRepository as Repository;

class VideoController extends BaseViewController
{

  protected $repository;

  public function __construct(Repository $repository)
  {
    $this->repository = $repository;

    $this->init([
      'body' => [
        'title' => 'วิดิโอทั้งหมด',
        'description' => 'วิดิโอทั้งหมด'
      ]
    ]);
  }

  public function index(Request $request)
  {
    $data['lists'] = $this->repository->paginate($request->all(), [
      'sort' => 'ASC',
      'id' => 'DESC',
    ]);

    return $this->render('video::index', $data);
  }

  public function show($slug)
  {
    $result = $this->repository->getSlug($slug);

    $this->init([
      'body' => [
        'title' => $result->title,
        'description' => $result->title
      ]
    ]);

    $data['result'] = $result;

    return $this->render('video::show', $data);
  }
}
