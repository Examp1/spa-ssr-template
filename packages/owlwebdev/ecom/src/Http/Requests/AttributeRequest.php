<?php

namespace Owlwebdev\Ecom\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttributeRequest extends FormRequest
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
            'slug' => 'nullable|string|max:120|unique:attributes,slug,' . $this->id, // id must be in POST
            'type' => 'required|string',
            'order' => 'nullable|numeric',

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
