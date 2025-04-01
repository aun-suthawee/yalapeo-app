<?php

namespace Modules\Banner\Repositories;

use App\CustomClass\ItopCyberUpload;
use Modules\Banner\Entities\Banner;
use App\Repositories\BaseRepository;
use Storage;

class BannerRepository extends BaseRepository
{
  public function __construct()
  {
    $this->init([
      'class_model_name' => Banner::class,
      'uri_prefix' => 'banner.large'
    ]);
  }

  public function ctlUploadOption()
  {
    return [
      'crop' => [
        'width' => 1920,
        'height' => 476,
        'watermark' => ""
      ]
    ];
  }

  public function ctlUpload($file, $id)
  {
    $exp = explode("\\", $this->classModelName);
    $sub_folder = gen_folder($id);
    $folder = strtolower(end($exp)) . '/large/' . $sub_folder;

    $options = $this->ctlUploadOption();

    return ItopCyberUpload::upload(storage_path('app/public/' . $folder), $file, $options);
  }

  /**
   * @param mixed $id
   * @param mixed $file_name
   * @return void
   */
  public function storageDelete($id, $file_name)
  {
    $exp = explode("\\", $this->classModelName);
    $folder = strtolower(end($exp)) . '/large/' . gen_folder($id);

    Storage::disk('public')->delete("$folder/$file_name");
    Storage::disk('public')->delete("$folder/crop/$file_name");
    Storage::disk('public')->delete("$folder/thumbnail/$file_name");
    Storage::disk('public')->delete("$folder/resize/$file_name");
  }

  public function limit($sort = [], $limit = 10)
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

      $item->cover = _fileExists('banner/large/' . gen_folder($item->id) . '/crop', $item->cover) ?
        Storage::url('banner/large/' . gen_folder($item->id) . '/crop/' . $item->cover) :
        __via_placeholder(1920, 476);

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

      $item->cover = _fileExists('banner/large/' . gen_folder($item->id) . '/crop', $item->cover) ?
        Storage::url('banner/large/' . gen_folder($item->id) . '/crop/' . $item->cover) :
        __via_placeholder(1920, 476);

      return $item;
    });

    return $result;
  }

  public function redirectToList()
  {
    return redirect()->route('admin.banner.large.index');
  }
}
