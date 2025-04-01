<?php

namespace Modules\Menu\Http\Controllers;

use App\Http\Controllers\BaseViewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Phattarachai\LineNotify\Facade\Line;
use Modules\Menu\Repositories\MenutopRepository as Repository;

class MenutopController extends BaseViewController
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

    return $this->render('menu::menu_top.show', $data);
  }
}
