<?php

namespace Modules\Page\Repositories;

use Modules\Page\Entities\Page;
use App\Repositories\BaseRepository;
use Storage;

class PageRepository extends BaseRepository
{
  public function __construct()
  {
    $this->init([
      'class_model_name' => Page::class,
      'uri_prefix' => 'page'
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
      if (!isset($item->url)) {
        $uri = $item->id;
        if (isset($item->slug)) {
          $uri = $item->slug;
        }

        $item->url = url($this->uriPrefix . '/' . $uri);
      }

      return $item;
    });
  }

  public function paginate($request = [], $sort = [], $limit = 20)
  {
    $q = $this->classModelName::query();

    if (!empty($request) && isset($request['search'])) {
      if ($request['search'] != "") {
        $q->where("title", "like", "%{$request['search']}%");
      }
    }

    if (empty($sort)) {
      $q->orderBy('id', 'DESC');
    } else {
      foreach ($sort as $field => $option) {
        $q->orderBy($field, $option);
      }
    }

    if (isset($request['export'])) {
      return $q->get();
    }

    $result = $q->paginate($limit);
    $result->getCollection()->transform(function ($item) {
      if (!isset($item->url)) {
        $uri = $item->id;
        if (isset($item->slug)) {
          $uri = $item->slug;
        }

        $item->url = url($this->uriPrefix . '/' . $uri);
      }

      $item->publish_url = $item->url;
      if (!isset($item->url)) {
        if ($item->url == "") {
          $item->publish_url = $this->uriPrefix != "" ? route($this->uriPrefix . '.show', $item->slug) : "";
        }
      }

      return $item;
    });

    return $result;
  }
}
