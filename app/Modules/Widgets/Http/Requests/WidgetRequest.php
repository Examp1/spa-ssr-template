<?php

namespace App\Modules\Widgets\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WidgetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'     => 'nullable|string|max:255',
            'instance' => 'required|string|max:255',
            'lang'     => 'required|string|max:2',
        ];
    }

    public function messages()
    {
        return [
            'instance.required' => "Шаблон – обов'язкове поле"
        ];
    }
}
