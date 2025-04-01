<?php

namespace Modules\Webboard\Entities;

use App\Models\BaseModel;

class Answer extends BaseModel
{
  protected $table = 'webboard_answers';
  protected $fillable = [
    'author',
    'ip',
    'detail',
    'webboard_id',
  ];

  public function setAuthorAttribute($value)
  {
    $this->attributes['author'] = $value;
    $this->attributes['ip'] = $_SERVER['REMOTE_ADDR'];
  }
}
