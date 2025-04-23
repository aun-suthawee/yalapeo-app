<?php

namespace Modules\News\Repositories;

use Modules\News\Entities\NewsType;
use App\Repositories\BaseRepository;

class NewsTypeRepository extends BaseRepository
{
  public function __construct()
  {
    $this->init([
      'class_model_name' => NewsType::class
    ]);
  }

  /**
   * @return mixed
   */
  public function getAll($homepage = true)
  {
    $q = $this->classModelName::query();
    if ($homepage) {
      $q->whereIn('title', ['ข่าวกิจกรรม', 'ข่าวประกาศ', 'ประชาสัมพันธ์']);
    }

    $q->orderBy('sort')->orderByDesc('id');

    return $q->get();
  }

  public function redirectToList()
  {
    return redirect()->route('admin.news.type.index');
  }
}
