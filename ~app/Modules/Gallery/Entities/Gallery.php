<?php

namespace Modules\Gallery\Entities;

use App\Models\BaseModel;

class Gallery extends BaseModel
{
  protected $table = 'galleries';
  protected $fillable = [
    'title',
    'url',
    'target',
    'slug',
    'detail',
    'sort',
    'cover',
    'slider'
  ];

  public function setTitleAttribute($value)
  {
    $this->attributes['title'] = $value;
    $this->attributes['slug'] = genSlug($value);
  }

  public function getPublishDateAttribute()
  {
    return thaiDate($this->created_at, 'summary');
  }
}
