<?php

namespace Modules\User\Repositories;

use Storage;
use Modules\User\Entities\User;
use App\CustomClass\ItopCyberUpload;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository
{
  public function __construct()
  {
    $this->init([
      'class_model_name' => User::class
    ]);
  }

  public function get($id)
  {
    return $this->classModelName::findOrFail($id);
  }

  public function ctlUploadOption(): array
  {
    return [
      'crop' => [
        'width' => 160,
        'height' => 160
      ]
    ];
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
    $q->where('username', '!=', 'superadmin');

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

  public function create($request)
  {
    $result = $this->classModelName::create($request);
    if ($result) {
      if (isset($request['cover'])) {
        $this->classModelName::where('id', $result->id)->update([
          'avatar' => $this->ctlUpload($_FILES['cover'], $result->id)
        ]);
      }
    }

    return $result;
  }

  public function update($request, $id)
  {
    $result = $this->classModelName::findOrFail($id);
    $cover = $result->cover;
    $result->update($request);
    if ($result) {
      if (isset($request['cover'])) {
        $this->storageDelete($id, $cover);

        $this->classModelName::where('id', $id)->update([
          'avatar' => $this->ctlUpload($_FILES['cover'], $id)
        ]);
      }
    }

    return $result;
  }

  public function destroy($id)
  {
    $result = $this->classModelName::findOrFail($id);

    return $result->delete();
  }

  public function storageDelete($id, $file_name)
  {
    $folder = 'avatar/' . gen_folder($id);

    Storage::disk('public')->delete("$folder/$file_name");
    Storage::disk('public')->delete("$folder/crop/$file_name");
    Storage::disk('public')->delete("$folder/thumbnail/$file_name");
    Storage::disk('public')->delete("$folder/resize/$file_name");
  }

  public function ctlUpload($file, $id)
  {
    $sub_folder = gen_folder($id);
    $folder = 'avatar/' . $sub_folder;

    $options = $this->ctlUploadOption();

    return ItopCyberUpload::upload(storage_path('app/public/' . $folder), $file, $options);
  }
}
