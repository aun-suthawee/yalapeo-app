<?php

namespace Modules\Webboard\Repositories;

use Modules\Webboard\Entities\Answer;
use App\Repositories\BaseRepository;

class AnswerRepository extends BaseRepository
{
  public function __construct()
  {
    $this->init([
      'class_model_name' => Answer::class,
      'uri_prefix' => 'webboard'
    ]);
  }

  public function redirectToList()
  {
    return redirect()->route('admin.webboard.show', request()->webboard);
  }
}
