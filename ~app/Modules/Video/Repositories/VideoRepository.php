<?php

namespace Modules\Video\Repositories;

use App\Repositories\BaseRepository;
use Modules\Video\Entities\Video;

class VideoRepository extends BaseRepository
{

  public function __construct()
  {
    $this->init([
      'class_model_name' => Video::class,
      'uri_prefix' => 'video'
    ]);
  }

  /**
   * @param array $sort
   * @param int $limit
   * @return mixed
   */
  public function limit($sort = [], $limit = 20)
  {
    $q = $this->classModelName::query();
    if (empty($sort)) {
      $q->orderBy('id', 'DESC');
    } else {
      foreach ($sort as $field => $option) {
        $q->orderBy($field, $option);
      }
    }

    $result = $q->limit($limit)->get();

    return collect($result)->map(function ($item) {
      $item->url = url($this->uriPrefix . '/' . $item->slug);

      return $item;
    });
  }

  public function paginate($condition = [], $sort = [], $limit = 20)
  {
    $q = $this->classModelName::query();

    if (!empty($condition) && isset($condition['search'])) {
      if ($condition['search'] != "") {
        $q->where("title", "like", "%{$condition['search']}%");
      }
    }

    if (empty($sort)) {
      $q->orderBy('id', 'DESC');
    } else {
      foreach ($sort as $field => $option) {
        $q->orderBy($field, $option);
      }
    }

    if (isset($condition['export'])) {
      return $q->get();
    }

    $result = $q->paginate($limit);
    $result->getCollection()->transform(function ($item) {
      $item->url = 'xxx5'; //url($this->uriPrefix . '/' . $item->slug);

      return $item;
    });

    return $result;
  }

  public function redirectToList()
  {
    return redirect()->route('admin.video.index');
  }
}
