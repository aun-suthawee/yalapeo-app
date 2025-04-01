<?php

namespace Modules\Assessment\Http\Controllers;

use App\Http\Controllers\BaseViewController;
use Modules\Assessment\Repositories\LpaRepository as Repository;

class LpaController extends BaseViewController
{
  protected $repository;

  public function __construct(Repository $repository)
  {
    $this->repository = $repository;

    $this->init([
      'body' => [
        'title' => 'Lpa',
        'description'  => 'Lpa'
      ],
      'breadcrumb' => [
        'class_name' => 'LpaBreadcrumb'
      ]
    ]);
  }

  public function index()
  {
    $data['lpas'] = $this->repository->lpasDistinctYear();

    return $this->render('assessment::lpa.index', $data);
  }

  public function show($year)
  {
    $result = $this->repository->lpasOfYear($year);

    $this->init([
      'body' => [
        'title' => "ITA $year",
        'description'  => "ITA $year"
      ]
    ]);

    $data['result'] = $result;

    return $this->render('assessment::lpa.show', $data);
  }
}
