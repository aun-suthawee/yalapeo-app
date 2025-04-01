<?php

namespace Modules\News\Entities;

use App\Casts\Json;
use App\Models\BaseModel;

/**
 * @property mixed $date
 */
class News extends BaseModel
{
  protected $table = 'news';

  protected $fillable = [
    'title',
    'slug',
    'description',
    'url',
    'target',
    'date',
    'detail',
    'view',
    'type_id',
    'cover',
    'attach',
  ];

  protected $appends = [
    'date_publish_format_1',
  ];

  protected $casts = [
    'attach' => Json::class,
  ];

  public function setTitleAttribute($value)
  {
    $this->attributes['title'] = $value;
    $this->attributes['slug'] = genSlug($value);
  }

  public function type()
  {
    return $this->belongsTo(NewsType::class, 'type_id', 'id');
  }

  public function getDatePublishFormat1Attribute($key): string
  {
    return thaiDate($this->date, 'short');
  }
}
