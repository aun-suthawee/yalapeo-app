<?php

namespace Modules\Menu\Repositories;

use App\Repositories\BaseRepository;
use Modules\Menu\Entities\Menutop;

class MenutopRepository extends BaseRepository
{
  public function __construct()
  {
    $this->init([
      'class_model_name' => Menutop::class
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
          $level1->url = url($level1->slug);
        }

        $level1->is_level3 = false;

        if (count($level1->children)) {
          foreach ($level1->children as $level2) {
            if ($level2->url == "") {
              $level2->url = url($level2->slug);
            }

            if (count($level2->children)) {
              $level1->is_level3 = true;

              foreach ($level2->children as $level3) {
                if ($level3->url == "") {
                  $level3->url = url($level3->slug);
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
    return redirect()->route('admin.menu-top.index');
  }
}
