<?php

namespace Modules\Assessment\Http\Controllers;

use App\Http\Controllers\BaseViewController;
use Modules\Assessment\Repositories\ItaRepository;

class ItaController extends BaseViewController
{
  protected $repository;

  public function __construct(ItaRepository $repository)
  {
    $this->repository = $repository;

    $this->init([
      'body' => [
        'title' => 'Ita',
        'description'  => 'Ita'
      ],
      'breadcrumb' => [
        'class_name' => 'ItaBreadcrumb'
      ]
    ]);
  }

  public function index()
  {
    $data['itas'] = $this->repository->itasDistinctYear();

    return $this->render('assessment::ita.index', $data);
  }

  public function show($year)
  {
    $result = $this->repository->itasOfYear($year);

    $this->init([
      'body' => [
        'title' => "ITA $year",
        'description'  => "ITA $year"
      ]
    ]);

    $data['result'] = $result;

    return $this->render('assessment::ita.show', $data);
  }
}
