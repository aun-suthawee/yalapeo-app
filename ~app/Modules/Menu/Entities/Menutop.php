<?php

namespace Modules\Menu\Entities;

use App\Models\BaseModel;

/**
 * @method static select(string[] $array)
 */
class Menutop extends BaseModel
{
  protected $table = 'menu_top';
  protected $fillable = [
    'parent_id',
    'name',
    'slug',
    'url',
    'target',
    'detail',
    'sort',
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
