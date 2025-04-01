<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $date
 * @property mixed $created_at
 */
class BaseModel extends Model
{

  protected $appends = [
    'date_created_format_1',
    'date_created_format_2',
  ];

  public function getCreatedAtAttribute($key)
  {
    return $key ?? date('Y-m-d [H:i:s] A ', strtotime($key));
  }

  public function getUpdatedAtAttribute($key)
  {
    return $key ?? date('Y-m-d [H:i:s] A ', strtotime($key));
  }

  public function getDateCreatedFormat1Attribute($key)
  {
    return $key ?? date('d/m/Y [H:i:s] A ', strtotime($this->created_at));
  }

  public function getDateCreatedFormat2Attribute(): string
  {
    return thaiDate($this->created_at, 'short');
  }

}
