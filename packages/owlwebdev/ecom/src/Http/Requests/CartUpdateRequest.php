<?php

namespace Owlwebdev\Ecom\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'count' => 'required|integer',
            'coupon' => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:3000',
        ];
    }
}
