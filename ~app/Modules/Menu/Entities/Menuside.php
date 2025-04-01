<?php

namespace Modules\Menu\Entities;

use App\Casts\Json;
use App\Models\BaseModel;

/**
 * @method static select(string[] $array)
 */
class Menuside extends BaseModel
{
  protected $table = 'menu_sides';
  protected $fillable = [
    'parent_id',
    'name',
    'slug',
    'url',
    'target',
    'detail',
    'sort',
    'attach',
  ];

  protected $casts = [
    'attach' => Json::class,
  ];

  public function setNameAttribute($value)
  {
    $this->attributes['name'] = $value;
    $this->attributes['slug'] = genSlug($value);
  }

  public function children()
  {
    return $this->hasMany(self::class, 'parent_id')
      ->orderBy('sort', 'asc')
      ->orderBy('id', 'desc');
  }

  public function parent()
  {
    return $this->hasMany(self::class, 'id', 'parent_id')
      ->orderBy('sort', 'asc')
      ->orderBy('id', 'desc');
  }
}
