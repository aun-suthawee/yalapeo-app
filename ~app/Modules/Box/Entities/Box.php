<?php

namespace Modules\Box\Entities;

use App\Models\BaseModel;

class Box extends BaseModel
{
  protected $table = 'boxs';
  protected $fillable = [
    'detail_1',
    'detail_2',
  ];
}
