<?php

namespace Modules\News\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsStoreRequest extends FormRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'title'     => 'required',
      'date' => 'required',
      'type_id' => 'required',
      'description' => 'required'
    ];

    // $rules = [
    //   'title'     => 'required',
    //   'date' => 'required',
    //   'type_id' => 'required',
    //   'description' => 'required'
    // ];

    // switch ($this->method()) {
    //   case 'POST':
    //     $rules = $rules + [
    //       'cover' => 'required|image|max:4096|mimes:jpg,jpeg,png,webp'
    //     ];

    //     return $rules;
    //   case 'PUT':
    //     $rules = $rules + [
    //       'cover' => 'image|max:4096|mimes:jpg,jpeg,png,webp'
    //     ];
    //     return $rules;
    //   default:
    //     return $rules;
    // }
  }

  /**
   * Custom message for validation
   *
   * @return array
   */
  public function messages()
  {
    return [
      'title.required' => 'โปรดระบุหัวข้อ',
      'date.required' => 'โปรดระบุวันที่เขียน',
      'type_id.required' => 'โปรดระบุประเภท',
      'description.required' => 'โปรดระบุคำอธิบาย',
      'cover.required' => 'โปรดแนบรูปภาพหน้าปก',
      'cover.max' => 'ภาพหน้าต้องมีขนาดไม่เกิน 4 MB',
      'cover.mimes' => 'ภาพหน้าปกต้องมีนามสกุล *.jpg, *.jpeg, *.png',
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
