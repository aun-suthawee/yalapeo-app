<?php

namespace Modules\Personal\Http\Controllers;

use App\Http\Controllers\BaseViewController;
use Modules\Personal\Repositories\PersonalRepository as Repository;
use Modules\Personal\Repositories\TreeRepository;

class PersonalController extends BaseViewController
{
  protected $repository;
  protected $treeRepository;

  public function __construct(
    Repository $repository,
    TreeRepository $treeRepository
  ) {
    $this->repository = $repository;
    $this->treeRepository = $treeRepository;

    $this->init([
      'body' => [
        'title' => 'ทำเนียบบุคลากรทั้งหมด',
        'description'  => 'ทำเนียบบุคลากรทั้งหมด'
      ]
    ]);
  }

  public function show($slug)
  {
    $level1 = $this->repository->getSlug($slug);

    // dd($level1);

    $this->init([
      'body' => [
        'title' => $level1->title,
        'description' => $level1->title
      ]
    ]);

    $data['level1'] = $level1;

    return $this->render('personal::show', $data);
  }
}
