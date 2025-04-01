<?php

namespace Modules\Book\Repositories;

use App\Repositories\BaseRepository;
use Modules\Book\Entities\Book;
use Storage;

class BookRepository extends BaseRepository
{
  public function __construct()
  {
    $this->init([
      'class_model_name' => Book::class,
      'uri_prefix' => 'book'
    ]);
  }

  public function ctlUploadOption()
  {
    return [
      'crop' => [
        'width' => 208,
        'height' => 302,
        'watermark' => ""
      ]
    ];
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

      $item->cover = _fileExists('book/' . gen_folder($item->id) . '/crop', $item->cover) ?
        Storage::url('book/' . gen_folder($item->id) . '/crop/' . $item->cover) :
        __via_placeholder(208, 302);

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
      if (!isset($item->url)) {
        $uri = $item->id;
        if (isset($item->slug)) {
          $uri = $item->slug;
        }

        $item->url = url($this->uriPrefix . '/' . $uri);
      }

      $item->cover = _fileExists('book/' . gen_folder($item->id) . '/crop', $item->cover) ?
        Storage::url('book/' . gen_folder($item->id) . '/crop/' . $item->cover) :
        __via_placeholder(208, 302);

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
