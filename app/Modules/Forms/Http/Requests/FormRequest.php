<?php

namespace App\Modules\Forms\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as FR;

class FormRequest extends FR
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
            'lang'     => 'required|string|max:2',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('Name is a required field'),
        ];
    }
}
