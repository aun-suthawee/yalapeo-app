<?php

namespace Modules\Personal\Repositories;

use Modules\Personal\Entities\Personal;
use App\Repositories\BaseRepository;
use Storage;

class PersonalRepository extends BaseRepository
{
  public function __construct()
  {
    $this->init([
      'class_model_name' => Personal::class,
      'uri_prefix' => 'personal'
    ]);
  }

  public function prepare()
  {
    $lists = $this->classModelName::query()
      ->orderBy('sort')
      ->orderByDesc('id')
      ->get();
    if (!empty($lists)) {
      foreach ($lists as $level1) {
        $level1->url = url('personal/' . $level1->slug);
      }
    }

    return $lists;
  }

  public function getSlug($slug)
  {
    $lists = $this->classModelName::query()
      ->with(['tree'])
      ->where("slug", $slug)
      ->orderBy('sort')
      ->orderByDesc('id')
      ->firstOrFail();

    if (count($lists->tree)) {
      foreach ($lists->tree as $value) {
        $value->cover = _fileExists('tree/' . gen_folder($value->id) . '/crop', $value->cover) ?
          Storage::url('tree/' . gen_folder($value->id) . '/crop/' . $value->cover) :
          __via_placeholder(120, 160);
        $tree_format[$value->sequent_row][] = $value;
      }

      $lists->tree_format = $tree_format;
    }

    return $lists;
  }

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
    return redirect()->route('admin.personal.index');
  }
}
