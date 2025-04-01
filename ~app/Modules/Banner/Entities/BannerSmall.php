<?php

namespace Modules\Banner\Entities;

use App\Models\BaseModel;

class BannerSmall extends BaseModel
{
  protected $table = 'banner_smalls';
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
