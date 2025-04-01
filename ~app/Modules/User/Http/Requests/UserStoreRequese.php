<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\User\Rules\UsernameFormatPattern;

class UserStoreRequese extends FormRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    $rules = [
      'name'     => ['required'],
      'nickname' => ['required']
    ];

    switch ($this->method()) {
      case 'POST':
        $rules = $rules + [
          'username' => ['required', 'unique:users', new UsernameFormatPattern],
          'password' => ['required'],
          'email'    => ['required', 'email', 'max:255', 'unique:users'],
          'cover'    => ['required', 'image', 'max:1024', 'mimes:jpg,jpeg,png,webp']
        ];

        return $rules;
      case 'PUT':
        $rules = $rules + [
          'cover'    => ['image', 'max:1024', 'mimes:jpg,jpeg,png,webp']
        ];
        return $rules;
      default:
        return $rules;
    }
  }

  /**
   * Custom message for validation
   *
   * @return array
   */
  public function messages()
  {
    return [
      'username.required' => 'โปรดระบุชื่อผู้ใช้งาน',
      'username.unique'   => 'ชื่อผู้ใช้งานนี้ถูกใช้ไปแล้ว',
      'password.required' => 'โปรดระบุรหัสผ่าน',
      'email.required'    => 'โปรดระบุอีเมล์',
      'email.unique'      => 'อีเมล์นี้ถูกใช้ไปแล้ว',
      'name.required'     => 'อีเมล์นี้ถูกใช้ไปแล้ว',
      'name.required'     => 'โปรดระบุชื่อ-นามสกุล',
      'nickname.required' => 'โปรดระบุเล่น',
      'cover.required'    => 'โปรดแนบรูปภาพ',
      'cover.max'         => 'ภาพหน้าต้องมีขนาดไม่เกิน 1 MB',
      'cover.mimes'       => 'ภาพหน้าปกต้องมีนามสกุล *.jpg, *.jpeg, *.png'
    ];
  }

  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }
}
