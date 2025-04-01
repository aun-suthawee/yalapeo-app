<?php

namespace Modules\Book\Http\Controllers;

use App\Http\Controllers\BaseViewController;
use Illuminate\Http\Request;
use Modules\Book\Repositories\BookRepository as Repository;

class BookController extends BaseViewController
{
  protected $repository;

  public function __construct(Repository $repository)
  {
    $this->repository = $repository;

    $this->init([
      'body' => [
        'title' => 'Book',
        'description'  => 'Book'
      ],
      'breadcrumb' => [
        'class_name' => 'BookBreadcrumb'
      ]
    ]);
  }

  /**
   * Method index
   *
   * @return void
   */
  public function index(Request $request)
  {
    $data['lists'] = $this->repository->paginate($request->all(), [
      'sort' => 'ASC',
      'id' => 'DESC',
    ]);

    return $this->render('book::index', $data);
  }


  /**
   * Method show
   *
   * @param $slug $slug [explicite description]
   *
   * @return void
   */
  public function show($slug)
  {
    $result = $this->repository->getSlug($slug);

    $this->init([
      'body' => [
        'title' => $result->title,
        'description'  => $result->title
      ]
    ]);

    $data['result'] = $result;

    return $this->render('book::show', $data);
  }
}
