<?php

namespace App\Repositories;

use Storage;
use App\CustomClass\ItopCyberUpload;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BaseRepository
{
  protected $classModelName;
  protected $uriPrefix;

  /**
   * @param array $config
   */
  public function init(array $config = [])
  {
    if (isset($config['class_model_name'])) {
      $this->classModelName = $config['class_model_name'];

      if (isset($config['uri_prefix'])) {
        $this->uriPrefix = $config['uri_prefix'];
      }
    } else {
      throw new HttpException(500, 'please check parameters configs.');
    }
  }

  /**
   * @return string
   */
  public static function __module()
  {
    $action = Route::getCurrentRoute()->getActionName();
    $exp = explode("\\", $action);

    return ucfirst($exp[1]);
  }

  /**
   * @param $id
   * @return mixed
   */
  public function get($id)
  {
    $result = $this->classModelName::findOrFail($id);
    $slug = "";
    if (!isset($result->url)) {
      if (isset($result->slug)) {
        $slug = $result->id;
      }

      $result->publish_url = url($this->uriPrefix . '/' . $slug);
    }

    return $result;
  }

  /**
   * @param $slug
   * @return mixed
   */
  public function getSlug($slug)
  {
    $result = $this->classModelName::where("slug", $slug)->firstOrFail();
    $slug = "";
    if (!isset($result->url)) {
      if (isset($result->slug)) {
        $slug = $result->id;
      }

      $result->publish_url = url($this->uriPrefix . '/' . $slug);
    }

    return $result;
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

  /**
   * @param array $sort
   * @return mixed
   */
  function list($sort = [])
  {
    $q = $this->classModelName::query();
    if (empty($sort)) {
      $q->orderBy('id', 'DESC');
    } else {
      foreach ($sort as $field => $option) {
        $q->orderBy($field, $option);
      }
    }

    $result = $q->get();

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

  /**
   * @param array $sort
   * @param int $limit
   * @param array $condition
   * @return mixed
   */
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

    return $q->paginate($limit);
  }

  /**
   * @param array $array
   * @return mixed
   */
  public function search($array = [])
  {
    $q = $this->classModelName::query();
    if ($array['code'] != "") {
      $q->where("code", $array['code']);
    }
    $q->orderBy('id', 'DESC');

    return $q->paginate(20);
  }

  /**
   * @param $request
   * @return mixed
   */
  public function create($request)
  {
    $result = $this->classModelName::create($request);
    if ($result) {
      if (isset($request['cover'])) {
        $this->classModelName::where('id', $result->id)->update([
          'cover' => $this->ctlUpload($_FILES['cover'], $result->id)
        ]);
      }
    }

    return $result;
  }

  /**
   * @param $request
   * @param $id
   * @return mixed
   */
  public function update($request, $id)
  {
    $result = $this->classModelName::findOrFail($id);
    $cover = $result->cover;
    $result->update($request);
    if ($result) {
      if (isset($request['cover'])) {
        $this->storageDelete($id, $cover);

        $this->classModelName::where('id', $id)->update([
          'cover' => $this->ctlUpload($_FILES['cover'], $id)
        ]);
      }
    }

    return $result;
  }

  /**
   * @param $id
   * @return mixed
   */
  public function destroy($id)
  {
    $result = $this->classModelName::findOrFail($id);

    return $result->delete();
  }

  /**
   * @param array $request
   * @return mixed
   */
  public function sort($request = [])
  {
    $output = [];
    parse_str($request['serialize'], $output);

    $j = 0;
    foreach ($output['item'] as $index => $value) {
      $j = $index + 1;
      $this->classModelName::where('id', $value)
        ->update(['sort' => $j]);
    }

    $q = $this->classModelName::query();
    $model = $q->whereNotIn('id', $output['item'])
      ->select(['id'])
      ->get()
      ->toArray();

    foreach ($model as $index => $value) {
      $j += 1;
      $this->classModelName::where('id', $value['id'])
        ->update(['sort' => $j]);
    }

    return $model;
  }

  /**
   * @return array[]
   */
  public function ctlUploadOption()
  {
    return [
      'thumbnail' => [
        'width' => 640,
        'height' => 480,
        'watermark' => "watermark_01"
      ],
      'resize' => [
        'width' => 640,
        'height' => 480,
        'watermark' => "watermark_01"
      ],
      'crop' => [
        'width' => 640,
        'height' => 480,
        'watermark' => "watermark_01"
      ]
    ];
  }

  /**
   * @param $file
   * @param $id
   * @return mixed
   */
  public function ctlUpload($file, $id)
  {
    $exp = explode("\\", $this->classModelName);
    $sub_folder = gen_folder($id);
    $folder = strtolower(end($exp)) . '/' . $sub_folder;

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
    $folder = strtolower(end($exp)) . '/' . gen_folder($id);

    Storage::disk('public')->delete("$folder/$file_name");
    Storage::disk('public')->delete("$folder/crop/$file_name");
    Storage::disk('public')->delete("$folder/thumbnail/$file_name");
    Storage::disk('public')->delete("$folder/resize/$file_name");
  }

  /**
   * @return \Illuminate\Http\RedirectResponse
   */
  public function redirectToList()
  {
    return redirect()->route('admin.' . strtolower(self::__module()) . '.index');
  }

  /**
   * @param $array
   * @param array $excludeKeys
   * @return mixed
   */
  public function arrayExclude($array, array $excludeKeys)
  {
    foreach ($excludeKeys as $key) {
      unset($array[$key]);
    }

    return $array;
  }
}
