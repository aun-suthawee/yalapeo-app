<?php

namespace Modules\Personal\Repositories;

use Modules\Personal\Entities\Tree;
use App\Repositories\BaseRepository;
use Storage;

class TreeRepository extends BaseRepository
{
  public function __construct()
  {
    $this->init([
      'class_model_name' => Tree::class,
      'uri_prefix' => 'personal'
    ]);
  }

  public function ctlUploadOption()
  {
    return [
      'crop' => [
        'width' => 120,
        'height' => 160,
        'watermark' => env('ITOPCY_UPLOAD_WATEMARK', "NAKOMAH STUDIO")
      ]
    ];
  }

  public function paginate($condition = [], $sort = [], $limit = 20)
  {
    $q = $this->classModelName::query();
    $q->where('personal_id', $condition['personal_id']);

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
    $result->map(function ($item) {
      $item->cover = _fileExists('tree/' . gen_folder($item->id) . '/crop', $item->cover) ?
        Storage::url('tree/' . gen_folder($item->id) . '/crop/' . $item->cover) :
        __via_placeholder(120, 160);

      return $item;
    });

    return $result;
  }

  public function prepare($condition = [])
  {
    $data = [];
    $q = $this->classModelName::query();
    $q->where('personal_id', $condition['personal_id']);

    $q->orderBy('sequent_row');
    $q->orderBy('sequent_col');

    $model = $q->get();
    foreach ($model as $value) {
      $data[$value->sequent_row][] = [
        'title' => $value->title,
        'position' => $value->position,
        'tel' => $value->tel,
        'sequent_row' => $value->sequent_row,
        'sequent_col' => $value->sequent_col,
        'cover' => _fileExists('tree/' . gen_folder($value->id) . '/crop', $value->cover) ?
          Storage::url('tree/' . gen_folder($value->id) . '/crop/' . $value->cover) :
          __via_placeholder(120, 160),
      ];
    }

    return $data;
  }

  public function redirectToList()
  {
    return redirect()->route('admin.personal.index');
  }
}
