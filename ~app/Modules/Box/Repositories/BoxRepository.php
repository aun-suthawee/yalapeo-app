<?php

namespace Modules\Box\Repositories;

use Modules\Box\Entities\Box;
use App\Repositories\BaseRepository;

class BoxRepository extends BaseRepository
{
  public function __construct()
  {
    $this->init([
      'class_model_name' => Box::class,
      'uri_prefix' => 'box'
    ]);
  }

  public function get($id)
  {
    return $this->classModelName::find($id);
  }
}
