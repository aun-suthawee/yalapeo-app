<?php

namespace Modules\Webboard\Entities;

use App\Models\BaseModel;

class Webboard extends BaseModel
{
  protected $table = 'webboards';
  protected $fillable = [
    'title',
    'slug',
    'author',
    'view',
    'ip',
    'detail',
  ];

  public function setTitleAttribute($value)
  {
    $this->attributes['title'] = $value;
    $this->attributes['slug'] = genSlug($value);
    $this->attributes['ip'] = $_SERVER['REMOTE_ADDR'];
  }

  public function answers()
  {
    return $this->hasMany(Answer::class, 'webboard_id');
  }
}
