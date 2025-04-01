<?php

namespace Modules\Assessment\Repositories;

use Modules\Assessment\Entities\Ita;
use App\Repositories\BaseRepository;

class ItaRepository extends BaseRepository
{
  public function __construct()
  {
    $this->init([
      'class_model_name' => Ita::class,
      'uri_prefix' => 'ita'
    ]);
  }

  public function itasOfYear($year)
  {
    $q = $this->classModelName::query();
    $q->where('year', $year);

    return $q->first();
  }

  public function itasDistinctYear()
  {
    $q = $this->classModelName::distinct();
    $result = $q->orderByDesc('year')->get('year')->toArray();

    return array_column($result, 'year');
  }

  public function redirectToList()
  {
    return redirect()->route('admin.' . strtolower($this->uriPrefix) . '.index');
  }
}
