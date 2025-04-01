<?php

namespace Modules\Banner\Repositories;

use App\CustomClass\ItopCyberUpload;
use Modules\Banner\Entities\BannerSmall;
use App\Repositories\BaseRepository;
use Storage;

class BannerSmallRepository extends BaseRepository
{
  public function __construct()
  {
    $this->init([
      'class_model_name' => BannerSmall::class,
      'uri_prefix' => 'banner.small'
    ]);
  }

  public function ctlUploadOption()
  {
    return [
      'crop' => [
        'width' => 966,
        'height' => 342,
        'watermark' => env('ITOPCY_UPLOAD_WATEMARK', "NAKOMAH STUDIO")
      ]
    ];
  }

  public function ctlUpload($file, $id)
  {
    // $exp = explode("\\", $this->classModelName);
    $sub_folder = gen_folder($id);
    $folder = 'banner/small/' . $sub_folder;

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
    $folder = strtolower(end($exp)) . '/small/' . gen_folder($id);

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

      $item->cover = _fileExists('banner/small/' . gen_folder($item->id) . '/crop', $item->cover) ?
        Storage::url('banner/small/' . gen_folder($item->id) . '/crop/' . $item->cover) :
        __via_placeholder(966, 342);

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
        __via_placeholder(966, 342);

      return $item;
    });

    return $result;
  }

  public function redirectToList()
  {
    return redirect()->route('admin.banner.small.index');
  }
}
