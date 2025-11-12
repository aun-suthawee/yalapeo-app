<?php

namespace Modules\News\Repositories;

use App\CustomClass\ItopCyberUpload;
use App\Repositories\BaseRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\News\Entities\News;
use Modules\News\Repositories\NewsTypeRepository as RepositoryNewsType;

class NewsRepository extends BaseRepository
{
  protected $repositoryNewType;
  
  protected $defaultCoverImage = 'assets/images/new_thumbnail.jpg';

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

    // ตรวจสอบโฟลเดอร์ถ้ายังไม่มีให้สร้าง
    if (!file_exists(storage_path('app/public/' . $folder))) {
        mkdir(storage_path('app/public/' . $folder), 0777, true);
    }

    return ItopCyberUpload::upload(storage_path('app/public/' . $folder), $file, $options);
  }

  public function storageAttachDelete($id, $file_name)
  {
    $exp = explode("\\", $this->classModelName);
    $sub_folder = gen_folder($id);
    $folder = strtolower(end($exp)) . "/{$sub_folder}/attach";
    $filePath = "$folder/$file_name";

    // ตรวจสอบว่าไฟล์มีอยู่จริงก่อนลบ
    if (Storage::disk('public')->exists($filePath)) {
      Storage::disk('public')->delete($filePath);
      return true;
    }
    
    // ถ้าไฟล์ไม่พบ ให้บันทึก log แต่ไม่ให้ error
    Log::warning("File not found for deletion: {$filePath}");
    return false;
  }

  public function create($request)
  {
    $result = $this->classModelName::create($this->arrayExclude($request, ['attach']));
    if ($result) {
      // Harden: อัปโหลดเฉพาะเคสที่มีไฟล์จริงและ tmp_name ชี้ไปยังไฟล์ที่อัปโหลด
      if (isset($_FILES['cover']) && !empty($_FILES['cover']['name']) && isset($_FILES['cover']['tmp_name'])
        && $_FILES['cover']['tmp_name'] !== '' && @is_uploaded_file($_FILES['cover']['tmp_name'])) {
        $uploadedName = $this->ctlUpload($_FILES['cover'], $result->id);
        $this->classModelName::where('id', $result->id)->update([
          'cover' => $uploadedName
        ]);
      } else {
        // กำหนดรูปภาพตั้งต้นเมื่อไม่มีการอัพโหลด
        $this->classModelName::where('id', $result->id)->update([
          'cover' => $this->defaultCoverImage
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
      if (isset($_FILES['cover']) && !empty($_FILES['cover']['name']) && isset($_FILES['cover']['tmp_name'])
        && $_FILES['cover']['tmp_name'] !== '' && @is_uploaded_file($_FILES['cover']['tmp_name'])) {
        if ($cover !== $this->defaultCoverImage) {
          $this->storageDelete($id, $cover);
        }
        $uploadedName = $this->ctlUpload($_FILES['cover'], $id);
        $this->classModelName::where('id', $id)->update([
          'cover' => $uploadedName
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

      // ตรวจสอบว่าภาพ cover เป็น default หรือไม่
      if ($item->cover === $this->defaultCoverImage) {
        $item->cover = asset($this->defaultCoverImage);
      } else {
          $cropFolder = 'news/' . gen_folder($item->id) . '/crop';
          $baseFolder = 'news/' . gen_folder($item->id);
          if (_fileExists($cropFolder, $item->cover)) {
            $item->cover = Storage::url($cropFolder . '/' . $item->cover);
          } elseif (_fileExists($baseFolder, $item->cover)) {
            $item->cover = Storage::url($baseFolder . '/' . $item->cover);
          } else {
            $item->cover = asset($this->defaultCoverImage);
          }
      }

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

      // ตรวจสอบว่าภาพ cover เป็น default หรือไม่
      if ($item->cover === $this->defaultCoverImage) {
        $item->cover = asset($this->defaultCoverImage);
      } else {
          $cropFolder = 'news/' . gen_folder($item->id) . '/crop';
          $baseFolder = 'news/' . gen_folder($item->id);
          if (_fileExists($cropFolder, $item->cover)) {
            $item->cover = Storage::url($cropFolder . '/' . $item->cover);
          } elseif (_fileExists($baseFolder, $item->cover)) {
            $item->cover = Storage::url($baseFolder . '/' . $item->cover);
          } else {
            $item->cover = asset($this->defaultCoverImage);
          }
      }

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

      // ตรวจสอบว่าภาพ cover เป็น default หรือไม่
      if ($item->cover === $this->defaultCoverImage) {
        $item->cover = asset($this->defaultCoverImage);
      } else {
          $cropFolder = 'news/' . gen_folder($item->id) . '/crop';
          $baseFolder = 'news/' . gen_folder($item->id);
          if (_fileExists($cropFolder, $item->cover)) {
            $item->cover = Storage::url($cropFolder . '/' . $item->cover);
          } elseif (_fileExists($baseFolder, $item->cover)) {
            $item->cover = Storage::url($baseFolder . '/' . $item->cover);
          } else {
            $item->cover = asset($this->defaultCoverImage);
          }
      }

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
        
        // ตรวจสอบว่าภาพ cover เป็น default หรือไม่ (ไม่แก้บล็อกนี้เพื่อให้ lists type all ยังใช้ตรรกะเดิม)
        if ($item->cover === $this->defaultCoverImage) {
          $item->cover = asset($this->defaultCoverImage);
        } else {
          $cropFolder = 'news/' . gen_folder($item->id) . '/crop';
          $baseFolder = 'news/' . gen_folder($item->id);
          if (_fileExists($cropFolder, $item->cover)) {
            $item->cover = Storage::url($cropFolder . '/' . $item->cover);
          } elseif (_fileExists($baseFolder, $item->cover)) {
            $item->cover = Storage::url($baseFolder . '/' . $item->cover);
          } else {
            $item->cover = asset($this->defaultCoverImage);
          }
        }

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

  public function storageDelete($id, $filename)
  {
    // ไม่ลบไฟล์ถ้าเป็นภาพ default
    if ($filename === $this->defaultCoverImage) {
      return;
    }
    
    // ลบไฟล์จาก storage ตามปกติ
    $exp = explode("\\", $this->classModelName);
    $sub_folder = gen_folder($id);
    $folder = strtolower(end($exp)) . "/{$sub_folder}";
    
    Storage::disk('public')->delete("$folder/$filename");
    Storage::disk('public')->delete("$folder/crop/$filename");
  }
}

