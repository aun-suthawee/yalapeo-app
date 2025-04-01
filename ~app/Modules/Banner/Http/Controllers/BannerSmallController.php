<?php

namespace Modules\Banner\Http\Controllers;

use App\Http\Controllers\BaseViewController;
use Illuminate\Http\Response;
use Modules\Banner\Repositories\BannerSmallRepository as Repository;

class BannerSmallController extends BaseViewController
{
  protected $repository;

  public function __construct(Repository $repository)
  {
    $this->repository = $repository;

    $this->init([
      'body' => [
        'title' => 'แบนเนอร์ทั้งหมด',
        'description'  => 'แบนเนอร์ทั้งหมด'
      ]
    ]);
  }


  public function index()
  {
    return view('banner::index');
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

    return $this->render('banner::small.show', $data);
  }
}
