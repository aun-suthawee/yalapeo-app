<?php

namespace Modules\Gallery\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GalleryStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'title'  => 'required'
        ];

        switch ($this->method()) {
            case 'POST':
                $rules = $rules + [
                    'cover' => 'required|image|max:3072|mimes:jpg,jpeg,png'
                ];

                return $rules;
            case 'PUT':
                $rules = $rules + [
                    'cover' => 'image|max:3072|mimes:jpg,jpeg,png'
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
            'title.required' => 'โปรดระบุหัวข้อ',
            'cover.required' => 'โปรดแนบรูปภาพหน้าปก',
            'cover.max' => 'ภาพหน้าต้องมีขนาดไม่เกิน 3 MB',
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
