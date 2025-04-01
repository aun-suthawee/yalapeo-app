<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
  protected $table = 'setting_meta';
  protected $fillable = [
    'title',
    'description',
    'keywords'
  ];
}
