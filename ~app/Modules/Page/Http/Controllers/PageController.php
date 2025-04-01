<?php

namespace Modules\Page\Http\Controllers;

use App\Http\Controllers\BaseViewController;
use Illuminate\Http\Request;
use Modules\Page\Repositories\PageRepository as Repository;

class PageController extends BaseViewController
{
   protected $repository;

   public function __construct(Repository $repository)
   {
      $this->repository = $repository;

      $this->init([
         'body' => [
            'title' => 'Page',
            'description'  => 'Page'
         ],
         'breadcrumb' => [
            'class_name' => 'PageBreadcrumb'
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

      return $this->render('page::index', $data);
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

      return $this->render('page::show', $data);
   }
}
