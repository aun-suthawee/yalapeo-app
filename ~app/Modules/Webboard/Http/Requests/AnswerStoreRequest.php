<?php

namespace Modules\Webboard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnswerStoreRequest extends FormRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'author'  => 'required',
      'g-recaptcha-response' => 'required|recaptcha'
    ];
  }

  /**
   * Custom message for validation
   *
   * @return array
   */
  public function messages()
  {
    return [
      'author.required' => 'โปรดระบุชื่อผู้ตอบ',
      'g-recaptcha-response.recaptcha' => 'การตรวจสอบ Captcha ล้มเหลว',
      'g-recaptcha-response.required' => 'กรุณากรอก captcha'
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
