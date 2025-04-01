<?php

namespace Modules\News\Repositories;

use App\CustomClass\ItopCyberUpload;
use App\Repositories\BaseRepository;
use Illuminate\Http\RedirectResponse;
use Modules\News\Entities\News;
use Modules\News\Repositories\NewsTypeRepository as RepositoryNewsType;
use Storage;
use Str;

class NewsRepository extends BaseRepository
{
  protected $repositoryNewType;

  public function __construct(RepositoryNewsType $repositoryNewType)
  {
    $this->repositoryNewType = $repositoryNewType;

    $this->init([
      'class_model_name' => News::class,
      'uri_prefix' => 'news'
    ]);
  }

  /**
   * @return array[]
   */
  public function ctlUploadOption()
  {
    return [
      'crop' => [
        'width' => 670,
        'height' => 405,
        'watermark' => env('ITOPCY_UPLOAD_WATEMARK', "NAKOMAH STUDIO")
      ]
    ];
  }

  /**
   * @return array[]
   */
  public function ctlUploadAttachOption()
  {
    return [];
  }

  public function ctlUploadAttach($file, $id)
  {
    $exp = explode("\\", $this->classModelName);
    $sub_folder = gen_folder($id);
    $folder = strtolower(end($exp)) . "/{$sub_folder}/attach";

    $options = $this->ctlUploadAttachOption();

    return ItopCyberUpload::upload(storage_path('app/public/' . $folder), $file, $options);
  }

  public function storageAttachDelete($id, $file_name)
  {
    $exp = explode("\\", $this->classModelName);
    $sub_folder = gen_folder($id);
    $folder = strtolower(end($exp)) . "/{$sub_folder}/attach";

    Storage::disk('public')->delete("$folder/$file_name");
  }

  public function create($request)
  {
    $result = $this->classModelName::create($this->arrayExclude($request, ['attach']));
    if ($result) {
      if (isset($request['cover'])) {
        $this->classModelName::where('id', $result->id)->update([
          'cover' => $this->ctlUpload($_FILES['cover'], $result->id)
        ]);
      }

      if (isset($request['attach'])) {
        $response = [];
        foreach ($request['attach'] as $value) {
          if ($value != '') {
            $requestFile = [
              'name' => $value->getClientOriginalName(),
              'type' => $value->getClientMimeType(),
              'tmp_name' => $value->getPathName(),
              'error' => $value->getError(),
              'size' => $value->getSize()
            ];

            $requestFile['name_uploaded'] = $this->ctlUploadAttach($requestFile, $result->id);
            $response[count($response)] = $requestFile;
          }
        }

        $this->classModelName::where('id', $result->id)->update([
          'attach' => $response
        ]);
      }
    }

    return $result;
  }

  public function update($request, $id)
  {
    $result = $this->classModelName::findOrFail($id);
    $cover = $result->cover;
    $attachs = $result->attach;
    $result->update($request);
    if ($result) {
      if (isset($request['cover'])) {
        $this->storageDelete($id, $cover);

        $this->classModelName::where('id', $id)->update([
          'cover' => $this->ctlUpload($_FILES['cover'], $id)
        ]);
      }

      if (isset($request['attach']) && isset($request['_token'])) {
        $response = ($attachs != null) ? $attachs : [];

        foreach ($request['attach'] as $value) {
          if ($value != '') {
            $requestFile = [
              'name' => $value->getClientOriginalName(),
              'type' => $value->getClientMimeType(),
              'tmp_name' => $value->getPathName(),
              'error' => $value->getError(),
              'size' => $value->getSize()
            ];

            $requestFile['name_uploaded'] = $this->ctlUploadAttach($requestFile, $result->id);
            $response[count($response)] = $requestFile;
          }
        }

        $this->classModelName::where('id', $result->id)->update([
          'attach' => $response
        ]);
      }
    }

    return $result;
  }

  public function paginate($condition = [], $sort = [], $limit = 20)
  {
    $q = $this->classModelName::query();

    if (!empty($condition) && isset($condition['type'])) {
      if ($condition['type'] != "") {
        $q->where('type_id', $condition['type']);
      }
    }

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

    $result = $q->paginate($limit);
    $result->map(function ($item) {
      $item->publish_url = $item->url;
      if (!isset($item->url)) {
        if ($item->url == "") {
          $item->publish_url = $this->uriPrefix != "" ? url($this->uriPrefix . '/' . $item->slug) : "";
        }
      }

      $item->cover = _fileExists('news/' . gen_folder($item->id) . '/crop', $item->cover) ?
        Storage::url('news/' . gen_folder($item->id) . '/crop/' . $item->cover) :
        __via_placeholder(370, 287);

      return $item;
    });

    return $result;
  }

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
      $item->publish_url = $item->url;
      if (!isset($item->url)) {
        if ($item->url == "") {
          $item->publish_url = $this->uriPrefix != "" ? url($this->uriPrefix . '/' . $item->slug) : "";
        }
      }

      $item->cover = _fileExists('news/' . gen_folder($item->id) . '/crop', $item->cover) ?
        Storage::url('news/' . gen_folder($item->id) . '/crop/' . $item->cover) :
        __via_placeholder(370, 287);

      return $item;
    });
  }

  public function getLastedNewOfActivity($sort = [], $limit = 20)
  {
    $q = $this->classModelName::query();
//    $q->orWhereHas('type', function ($query) {
//      $query->where('title', 'จัดซื้อจัดจ้าง');
//    });

    if (empty($sort)) {
      $q->orderBy('id', 'DESC');
    } else {
      foreach ($sort as $field => $option) {
        $q->orderBy($field, $option);
      }
    }

    $lists = $q->limit($limit)->get();
    return $lists->map(function ($item) {
      $item->publish_url = $item->url;
      if (!isset($item->url)) {
        if ($item->url == "") {
          $item->publish_url = $this->uriPrefix != "" ? url($this->uriPrefix . '/' . $item->slug) : "";
        }
      }

      $item->cover = _fileExists('news/' . gen_folder($item->id) . '/crop', $item->cover) ?
        Storage::url('news/' . gen_folder($item->id) . '/crop/' . $item->cover) :
        __via_placeholder(670, 405);

      return $item;
    });
  }

  public function getNewOfTypeAll($sort = [], $limit = 20): array
  {
    $response = [];
    $type_list = $this->repositoryNewType->getAll();
    foreach ($type_list as $index => $value) {
      $response[$index]['type_name'] = $value->title;

      $q = $this->classModelName::query();
      $q->where('type_id', $value->id);

      if (empty($sort)) {
        $q->orderBy('id', 'DESC');
      } else {
        foreach ($sort as $field => $option) {
          $q->orderBy($field, $option);
        }
      }

      $lists = $q->limit($limit)->get();
      $result = $lists->map(function ($item) {
        $item->publish_url = $item->url;
        if (!isset($item->url)) {
          if ($item->url == "") {
            $item->publish_url = $this->uriPrefix != "" ? url($this->uriPrefix . '/' . $item->slug) : "";
          }
        }

        $item->title = Str::limit(_stripTags($item->title), 35);
        $item->description = _stripTags($item->description);
        $item->cover = _fileExists('news/' . gen_folder($item->id) . '/crop', $item->cover) ?
          Storage::url('news/' . gen_folder($item->id) . '/crop/' . $item->cover) :
          __via_placeholder(298, 180);

        return $item;
      });

      $response[$index]['lists'] = $result;
    }

    return $response;
  }

  public function getNewOfTypePurchase($sort = [], $limit = 20)
  {
    $q = $this->classModelName::query();
    $q->orWhereHas('type', function ($query) {
      $query->where('title', 'จัดซื้อจัดจ้าง');
    });

    if (empty($sort)) {
      $q->orderBy('id', 'DESC');
    } else {
      foreach ($sort as $field => $option) {
        $q->orderBy($field, $option);
      }
    }

    $lists = $q->limit($limit)->get();
    return $lists->map(function ($item) {
      $item->publish_url = $item->url;
      if (!isset($item->url)) {
        if ($item->url == "") {
          $item->publish_url = $this->uriPrefix != "" ? route($this->uriPrefix . '.show', $item->slug) : "";
        }
      }

      return $item;
    });
  }

  public function getNewOfTypePurchaseSummary($sort = [], $limit = 20)
  {
    $q = $this->classModelName::query();
    $q->orWhereHas('type', function ($query) {
      $query->where('title', 'สรุปผลจัดซื้อจัดจ้างรายเดือน');
    });

    if (empty($sort)) {
      $q->orderBy('id', 'DESC');
    } else {
      foreach ($sort as $field => $option) {
        $q->orderBy($field, $option);
      }
    }

    $lists = $q->limit($limit)->get();
    return $lists->map(function ($item) {
      $item->publish_url = $item->url;
      if (!isset($item->url)) {
        if ($item->url == "") {
          $item->publish_url = $this->uriPrefix != "" ? route($this->uriPrefix . '.show', $item->slug) : "";
        }
      }

      return $item;
    });
  }

  public function redirectToList(): RedirectResponse
  {
    return redirect()->route('admin.news.index');
  }
}
