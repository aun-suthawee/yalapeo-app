<?php

namespace Modules\Book\Entities;

use App\Models\BaseModel;

class Book extends BaseModel
{
  protected $table = 'books';
  protected $fillable = [
    'title',
    'url',
    'target',
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
