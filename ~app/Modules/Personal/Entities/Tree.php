<?php

namespace Modules\Personal\Entities;

use App\Models\BaseModel;

class Tree extends BaseModel
{
  protected $table = 'personal_trees';
  protected $fillable = [
    'title',
    'position',
    'description',
    'email',
    'tel',
    'sequent_row',
    'sequent_col',
    'cover',
    'personal_id',
  ];
}
