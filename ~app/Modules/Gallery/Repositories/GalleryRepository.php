<?php

namespace Modules\Gallery\Repositories;

use App\CustomClass\ItopCyberUpload;
use Storage;
use App\Repositories\BaseRepository;
use Modules\Gallery\Entities\Gallery;

class GalleryRepository extends BaseRepository
{
  public function __construct()
  {
    $this->init([
      'class_model_name' => Gallery::class,
      'uri_prefix' => 'gallery'
    ]);
  }

  public function ctlUploadOption()
  {
    return [
      'crop' => [
        'width' => 480,
        'height' => 361,
        // 'watermark' => env('ITOPCY_UPLOAD_WATEMARK', "NAKOMAH STUDIO")
      ]
    ];
  }

  public function ctlUploadSliderOption()
  {
    return [
      'crop' => [
        'width' => 400,
        'height' => 266,
        // 'watermark' => env('ITOPCY_UPLOAD_WATEMARK', "NAKOMAH STUDIO")
      ]
    ];
  }

  public function ctlUploadSlider($file, $id)
  {
    $exp = explode("\\", $this->classModelName);
    $folder = strtolower(end($exp)) . '/' . gen_folder($id);

    $options = $this->ctlUploadSliderOption();

    return ItopCyberUpload::upload(storage_path('app/public/' . $folder), $file, $options);
  }

  /**
   * @param  mixed $id
   * @param  mixed $file_name
   * @return void
   */
  public function storageSliderDelete($id, $file_name)
  {
    $exp = explode("\\", $this->classModelName);
    $folder = strtolower(end($exp)) . '/' . gen_folder($id);

    Storage::disk('public')->delete("$folder/$file_name");
    Storage::disk('public')->delete("$folder/crop/$file_name");
  }

  /**
   * @param $id
   * @return mixed
   */
  public function get($id)
  {
    $result = $this->classModelName::findOrFail($id);
    $result->slider = json_decode($result->slider, true);

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

      $item->cover = _fileExists('gallery/' . gen_folder($item->id) . '/crop', $item->cover) ?
        Storage::url('gallery/' . gen_folder($item->id) . '/crop/' . $item->cover) :
        __via_placeholder(480, 361);

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

      $item->cover = _fileExists('gallery/' . gen_folder($item->id) . '/crop', $item->cover) ?
        Storage::url('gallery/' . gen_folder($item->id) . '/crop/' . $item->cover) :
        __via_placeholder(480, 361);

      return $item;
    });

    return $result;
  }

  /**
   * @param $request
   * @return mixed
   */
  public function create($request)
  {
    $result = $this->classModelName::create($this->arrayExclude($request, ['slider']));
    if ($result) {
      if (isset($request['cover'])) {
        $this->classModelName::where('id', $result->id)->update([
          'cover' => $this->ctlUpload($_FILES['cover'], $result->id)
        ]);
      }

      if (isset($request['slider'])) {
        $response = [];
        foreach ($request['slider'] as $value) {
          if ($value != '') {
            $requestFile = [
              'name' => $value->getClientOriginalName(),
              'type' => $value->getClientMimeType(),
              'tmp_name' => $value->getPathName(),
              'error' => $value->getError(),
              'size' => $value->getSize()
            ];
          }

          $requestFile['name_uploaded'] = $this->ctlUploadSlider($requestFile, $result->id);
          $response[count($response)] = $requestFile;
        }

        $this->classModelName::where('id', $result->id)->update([
          'slider' => _jsonUnescapedUnicode($response)
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
    $slider = $result->slider;
    $result->update($request);
    if ($result) {
      if (isset($request['cover'])) {
        $this->storageDelete($id, $cover);

        $this->classModelName::where('id', $result->id)->update([
          'cover' => $this->ctlUpload($_FILES['cover'], $result->id)
        ]);
      }

      if (isset($request['slider']) && isset($request['_token'])) {
        $response = ($slider != null) ? json_decode($slider, true) : [];

        foreach ($request['slider'] as $value) {
          if ($value != '') {
            $requestFile = [
              'name' => $value->getClientOriginalName(),
              'type' => $value->getClientMimeType(),
              'tmp_name' => $value->getPathName(),
              'error' => $value->getError(),
              'size' => $value->getSize()
            ];

            $requestFile['name_uploaded'] = $this->ctlUploadSlider($requestFile, $result->id);
            $response[count($response)] = $requestFile;
          }
        }

        $this->classModelName::where('id', $result->id)->update([
          'slider' => _jsonUnescapedUnicode($response)
        ]);
      }
    }

    return $result;
  }

  public function redirectToList()
  {
    return redirect()->route('admin.gallery.index');
  }
}
