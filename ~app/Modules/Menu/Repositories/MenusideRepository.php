<?php

namespace Modules\Menu\Repositories;

use App\CustomClass\ItopCyberUpload;
use App\Repositories\BaseRepository;
use Modules\Menu\Entities\Menuside;

class MenusideRepository extends BaseRepository
{
  public function __construct()
  {
    $this->init([
      'class_model_name' => Menuside::class
    ]);
  }

  /**
   * @param $id
   * @return mixed
   */
  public function get($id)
  {
    $result = $this->classModelName::findOrFail($id);
    $result->publish_url = $result->url;
    if (!isset($result->url)) {
      if ($result->url == "") {
        $result->publish_url = $this->uriPrefix != "" ? route($this->uriPrefix . '.show', $result->slug) : "";
      }
    }

    return $result;
  }


  /**
   * @param mixed $slug
   * @return mixed|void
   */
  public function getSlug($slug)
  {
    $result = $this->classModelName::where("slug", $slug)->firstOrFail();
    $result->publish_url = $result->url;
    if (!isset($result->url)) {
      if ($result->url == "") {
        $result->publish_url = $this->uriPrefix != "" ? route($this->uriPrefix . '.show', $result->slug) : "";
      }
    }

    return $result;
  }

  /**
   * @return array[]
   */
  public function ctlUploadOption()
  {
    return [];
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

    \Storage::disk('public')->delete("$folder/$file_name");
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

  /**
   * @return mixed
   */
  public function prepare()
  {
    $lists = $this->classModelName::whereNull('parent_id')
      ->with(['children', 'children.children'])
      ->select(['id', 'parent_id', 'name', 'slug', 'url', 'target'])
      ->orderBy('sort', 'asc')
      ->orderBy('id', 'desc')
      ->get();
    if (!empty($lists)) {
      foreach ($lists as $level1) {
        if ($level1->url == "") {
          $level1->url = url('content/' . $level1->slug);
        }

        $level1->is_level3 = false;

        if (count($level1->children)) {
          foreach ($level1->children as $level2) {
            if ($level2->url == "") {
              $level2->url = url('content/' . $level2->slug);
            }

            if (count($level2->children)) {
              $level1->is_level3 = true;

              foreach ($level2->children as $level3) {
                if ($level3->url == "") {
                  $level3->url = url('content/' . $level3->slug);
                }
              }
            }
          }
        }
      }
    }

    return $lists;
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

    return $output;
  }

  public function redirectToList()
  {
    return redirect()->route('admin.menu-side.index');
  }
}
