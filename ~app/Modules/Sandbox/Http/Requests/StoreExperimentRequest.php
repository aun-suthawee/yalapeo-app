<?php

namespace Modules\Sandbox\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExperimentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:what_if,scenario_comparison,impact_analysis',
            'base_year' => 'required|integer|min:2020|max:2030',
            'is_public' => 'boolean',
            'settings' => 'nullable|array',
            'settings.auto_calculate' => 'boolean',
            'settings.compare_mode' => 'in:absolute,percentage,both',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => 'ชื่อ Experiment',
            'description' => 'คำอธิบาย',
            'type' => 'ประเภท',
            'base_year' => 'ปีฐาน',
            'is_public' => 'สถานะสาธารณะ',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'กรุณาระบุชื่อ Experiment',
            'name.max' => 'ชื่อยาวเกินไป (สูงสุด 255 ตัวอักษร)',
            'type.required' => 'กรุณาเลือกประเภท Experiment',
            'type.in' => 'ประเภท Experiment ไม่ถูกต้อง',
            'base_year.required' => 'กรุณาระบุปีฐาน',
            'base_year.integer' => 'ปีฐานต้องเป็นตัวเลข',
            'base_year.min' => 'ปีฐานต้องไม่น้อยกว่า 2020',
            'base_year.max' => 'ปีฐานต้องไม่เกิน 2030',
        ];
    }
}
