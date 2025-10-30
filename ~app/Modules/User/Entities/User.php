<?php

namespace Modules\User\Entities;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
  use Notifiable, HasRoles;

  const ADMIN_TYPE = 1;
  const DEFAULT_TYPE = 0;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'username',
    'password',
    'email',
    'type',
    'name',
    'nickname',
    'position',
    'address',
    'city',
    'province_id',
    'zip_code'
  ];

  public function setPasswordAttribute($value)
  {
    $this->attributes['password'] = Hash::make($value);
  }

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public function isAdmin()
  {
    return $this->type === self::ADMIN_TYPE;
  }

  public function isMember()
  {
    return $this->type === self::DEFAULT_TYPE;
  }
}
