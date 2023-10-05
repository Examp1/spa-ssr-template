<?php

namespace Owlwebdev\Ecom\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
            'product_id' => 'required|numeric',
            'review_id'  => 'numeric|nullable',
            'user_id'    => 'numeric|nullable',
            'author'     => 'required|min:2|max:25',
            'city'       => 'string|max:50|nullable',
            'email'      => 'sometimes|email|nullable',
            'text'       => 'required|min:1|max:1500',
            'rating'     => 'required|numeric|min:1|max:5',
        ];
    }
}
