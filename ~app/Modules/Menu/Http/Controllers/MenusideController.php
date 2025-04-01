<?php

namespace Modules\Menu\Http\Controllers;

use App\Http\Controllers\BaseViewController;
use Modules\Menu\Repositories\MenusideRepository as Repository;

class MenusideController extends BaseViewController
{
  protected $repository;

  public function __construct(Repository $repository)
  {
    $this->repository = $repository;

    $this->init([
      'body' => [
        'title' => 'หน้าเมนู',
        'description'  => 'หน้าเมนู'
      ]
    ]);
  }

  public function show($slug)
  {
    $result = $this->repository->getSlug($slug);

    $this->init([
      'body' => [
        'title' => $result->name,
        'description' => $result->name
      ]
    ]);

    $data['result'] = $result;

    return $this->render('menu::menu_side.show', $data);
  }

  public function download($id, $download, $time)
  {
    $file = storage_path("app/public/menuside/" . gen_folder($id) . "/{$download}");
    if (file_exists($file)) {
      return response()->download($file);
    }
  }
}
