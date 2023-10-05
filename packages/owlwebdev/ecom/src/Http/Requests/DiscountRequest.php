<?php

namespace Owlwebdev\Ecom\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiscountRequest extends FormRequest
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
            'percentage' => 'required|integer|max:65535',
            'status' => 'required|boolean',

            'page_data.*.name' => 'required|string|max:255',
            'page_data.*.description' => 'nullable|string|max:65000',
        ];
    }

    public function messages()
    {
        return [
            'page_data.*.name.required' => __('Title is a required field'),
        ];
    }
}
