<?php

namespace Modules\Gallery\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\BaseViewController;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;
use Modules\Gallery\Repositories\GalleryRepository as Repository;

class GalleryController extends BaseViewController
{
  protected $repository;

  public function __construct(Repository $repository)
  {
    $this->repository = $repository;

    $this->init([
      'body' => [
        'title' => 'แกลเลอรี่',
        'description' => 'แกลเลอรี่'
      ],
      'breadcrumb' => [
        'class_name' => 'GalleryBreadcrumb'
      ]
    ]);
  }

  /**
   * Display a listing of the resource.
   * @return Response
   */
  public function index(Request $request)
  {
    $data['lists'] = $this->repository->paginate($request->all(), [
      'sort' => 'ASC',
      'id' => 'DESC',
    ]);

    return $this->render('gallery::index', $data);
  }

  /**
   * Show the specified resource.
   * @param int $id
   * @return Response
   */
  public function show($slug)
  {
    $data['stylesheets'] = [
      asset('assets/plugins/node_modules/lightgallery/dist/css/lightgallery.min.css')
    ];
    $data['scripts'] = [
      asset('assets/plugins/node_modules/lightgallery/dist/js/lightgallery.min.js'),
      asset('assets/plugins/node_modules/lightgallery/lib/jquery.mousewheel.min.js'),
      asset('assets/plugins/node_modules/lightgallery/modules/lg-thumbnail.min.js'),
      asset('assets/plugins/node_modules/lightgallery/modules/lg-fullscreen.min.js')
    ];

    $result = $this->repository->getSlug($slug);
    $result->slider = json_decode($result->slider, true);

    $this->init([
      'body' => [
        'title' => $result->title,
        'description' => $result->title
      ]
    ]);

    $data['result'] = $result;

    return $this->render('gallery::show', $data);
  }
}
