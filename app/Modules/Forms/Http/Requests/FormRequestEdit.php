<?php

namespace App\Modules\Forms\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as FR;

class FormRequestEdit extends FR
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
            'group_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('Name is a required field'),
            'group_id.required' => __('Group is a required field'),
        ];
    }
}
