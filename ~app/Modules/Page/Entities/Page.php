<?php

namespace Modules\Page\Entities;

use App\Models\BaseModel;

class Page extends BaseModel
{
  protected $table = 'pages';
  protected $fillable = [
    'title',
    'slug',
    'detail',
    'sort',
  ];

  public function setTitleAttribute($value)
  {
    $this->attributes['title'] = $value;
    $this->attributes['slug'] = genSlug($value);
  }
}
