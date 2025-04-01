<?php

namespace Modules\Assessment\Entities;

use App\Casts\Json;
use App\Models\BaseModel;

class Ita extends BaseModel
{
  protected $table = 'asm_itas';
  protected $fillable = [
    'year',
    'itas',
  ];

  protected $casts = [
    'itas' => Json::class,
  ];
}
