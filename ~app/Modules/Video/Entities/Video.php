<?php

namespace Modules\Video\Entities;

use App\Http\VideoEmbedTrait;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
  use VideoEmbedTrait;

  protected $table = 'videos';
  protected $fillable = [
    'title',
    'slug',
    'detail',
    'url',
    'output',
  ];

  public function setTitleAttribute($value)
  {
    $this->attributes['title'] = $value;
    $this->attributes['slug'] = genSlug($value);
  }
  public function setOutputAttribute($value)
  {
    $this->attributes['output'] = $this->Embed($value);
  }

  public function getCreatedAtAttribute($key)
  {
    return date('Y-m-d [H:i:s] A ', strtotime($key));
  }

  public function getUpdatedAtAttribute($key)
  {
    return date('Y-m-d [H:i:s] A ', strtotime($key));
  }
}
