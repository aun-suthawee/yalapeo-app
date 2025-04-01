<?php

namespace Modules\Banner\Entities;

use App\Models\BaseModel;

class Banner extends BaseModel
{
  protected $table = 'banners';

  protected $fillable = [
    'title',
    'url',
    'target',
    'slug',
    'detail',
    'sort',
    'cover',
  ];

  public function setTitleAttribute($value)
  {
    $this->attributes['title'] = $value;
    $this->attributes['slug'] = genSlug($value);
  }
}
