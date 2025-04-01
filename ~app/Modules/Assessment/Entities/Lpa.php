<?php

namespace Modules\Assessment\Entities;

use App\Casts\Json;
use App\Models\BaseModel;

class Lpa extends BaseModel
{
  protected $table = 'asm_lpas';
  protected $fillable = [
    'year',
    'lpas',
  ];

  protected $casts = [
    'lpas' => Json::class,
  ];
}
