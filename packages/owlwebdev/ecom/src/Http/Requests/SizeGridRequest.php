<?php

namespace Owlwebdev\Ecom\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SizeGridRequest extends FormRequest
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
        $defaultLang = config('translatable.locale');

        return [
            'page_data.' . $defaultLang . '.name' => 'required|min:2',
            'order'                               => 'nullable|integer',
        ];
    }

    public function messages()
    {
        $defaultLang = config('translatable.locale');

        return [
            'page_data.' . $defaultLang . '.name.required' => __('Name is a required field'),
            'page_data.' . $defaultLang . '.name.min'      => __('Name must be at least 2 characters'),
        ];
    }
}
