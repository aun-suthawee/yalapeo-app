<?php

namespace Modules\Webboard\Http\Controllers;

use App\Http\Controllers\BaseViewController;
use Modules\Webboard\Http\Requests\WebboardStoreRequest;
use Modules\Webboard\Repositories\WebboardRepository as Repository;

class WebboardController extends BaseViewController
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
    $data['lists'] = $this->repository->paginate();

    return view('webboard::index', $data);
  }


  public function create()
  {
    $this->init([
      'body' => [
        'title' => 'สร้างกระทู้',
        'description' => 'สร้างกระทู้'
      ]
    ]);

    $data['stylesheets'] = [
      asset('assets/plugins/summernote/summernote-bs4.min.css'),
    ];
    $data['scripts'] = [
      'https://www.google.com/recaptcha/api.js',
      asset('assets/plugins/summernote/summernote-bs4.min.js'),
      asset('assets/plugins/summernote/lang/summernote-th-TH.js'),
      asset('assets/@site_control/js/summernote-script.min.js'),
    ];

    return $this->render('webboard::create', $data);
  }

  public function store(WebboardStoreRequest $request)
  {
    $this->repository->create($request->all());

    return redirect()->route('webboard.index');
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
    $data['stylesheets'] = [
      asset('assets/plugins/summernote/summernote-bs4.min.css'),
    ];
    $data['scripts'] = [
      'https://www.google.com/recaptcha/api.js',
      asset('assets/plugins/summernote/summernote-bs4.min.js'),
      asset('assets/plugins/summernote/lang/summernote-th-TH.js'),
      asset('assets/@site_control/js/summernote-script.min.js'),
    ];

    return $this->render('webboard::show', $data);
  }
}
