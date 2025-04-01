<?php

namespace Modules\Webboard\Repositories;

use Modules\Webboard\Entities\Webboard;
use App\Repositories\BaseRepository;

class WebboardRepository extends BaseRepository
{
  public function __construct()
  {
    $this->init([
      'class_model_name' => Webboard::class,
      'uri_prefix' => 'webboard'
    ]);
  }

  public function redirectToList()
  {
    return redirect()->route('admin.webboard.index');
  }
}
