<?php

namespace Modules\Personal\Entities;

use App\Models\BaseModel;

class Personal extends BaseModel
{
  protected $table = 'personals';
  protected $fillable = [
    'title',
    'slug',
    'sort',
  ];

  public function tree()
  {
    return $this->hasMany(Tree::class, 'personal_id')
      ->orderBy('sequent_row')
      ->orderBy('sequent_col');
  }

  public function setTitleAttribute($value)
  {
    $this->attributes['title'] = $value;
    $this->attributes['slug'] = genSlug($value);
  }
}
