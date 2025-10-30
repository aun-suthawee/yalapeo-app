<?php

namespace Modules\News\Http\Controllers;

use App\Http\Controllers\BaseViewController;
use Illuminate\Http\Request;
use Modules\News\Repositories\NewsRepository;
use Modules\News\Repositories\NewsTypeRepository;

class NewsController extends BaseViewController
{
  protected $repository;
  protected $repositoryNewType;

  public function __construct(
    NewsRepository     $repository,
    NewsTypeRepository $repositoryNewType
  )
  {
    $this->repository = $repository;
    $this->repositoryNewType = $repositoryNewType;

    $this->init([
      'body' => [
        'title' => 'ข่าวสารทั้งหมด',
        'description' => 'ข่าวสารทั้งหมด'
      ]
    ]);
  }

  public function index(Request $request, $type = "")
  {
    $data['type_id'] = $type;
    $data['type_lists'] = $this->repositoryNewType->getAll(false);
    $data['lists'] = $this->repository->paginate($request->all(), [], 16);

    return $this->render('news::index', $data);
  }

  public function show($slug)
  {
    $result = $this->repository->getSlug($slug);
    
    $result->increment('view');

    $this->init([
      'body' => [
        'title' => $result->title,
        'description' => $result->title
      ]
    ]);

    $data['result'] = $result;

    return $this->render('news::show', $data);
  }

  public function download($id, $download, $time)
  {
    $file = storage_path("app/public/news/" . gen_folder($id) . "/attach/{$download}");
    if (file_exists($file)) {
      return response()->download($file);
    }
  }
}
