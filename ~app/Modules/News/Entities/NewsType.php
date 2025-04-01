<?php

namespace Modules\News\Entities;

use App\Models\BaseModel;

class NewsType extends BaseModel
{
  protected $table = 'news_types';
  protected $fillable = ['title'];

  public function news()
  {
    return $this->hasMany(News::class, 'type_id');
  }
}
