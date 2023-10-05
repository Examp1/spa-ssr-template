<?php

namespace Owlwebdev\Ecom\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_id' => 'required|integer',
            'count' => 'required|integer',
        ];
    }
}
