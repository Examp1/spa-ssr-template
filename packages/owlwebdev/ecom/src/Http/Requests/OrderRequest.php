<?php

namespace Owlwebdev\Ecom\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
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
        $data_validation = [];

        // if (/*$this->has('update_user') &&*/ $this->input('user.id') !== null) {
        //     $data_validation = array_merge($data_validation, [
        //         'user' => 'array',
        //         'user.name' => 'nullable|string|max:255',
        //         'user.lastname' => 'nullable|string|max:255',
        //         'user.email' => 'nullable|unique:users,email,' . $this->input('user.id'),
        //         'user.phone' => 'nullable|string|max:255',
        //         'user.country' => 'nullable|string|max:255',
        //         'user.city' => 'nullable|string|max:255',
        //         'user.address' => 'nullable|string|max:255',
        //         'user.postcode' => 'nullable|string|max:255',
        //         'user.comment' => 'nullable|string|max:255',
        //     ]);
        // }

        if ($this->has('user_id')) {
            $data_validation = array_merge($data_validation, [
                'user_id' => 'required|integer',
            ]);
        }

        //if ($this->has('update_shipping')) {
        $data_validation = array_merge($data_validation, [
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:255',
            'shipping_email' => 'nullable|string|max:255',
            'shipping_company' => 'nullable|string|max:255',
            'shipping_country' => 'nullable|string|max:255',
            'shipping_city' => 'nullable|string|max:255',
            'shipping_address' => 'nullable|string|max:255',
            'shipping_apartment' => 'nullable|string|max:255',
            'shipping_postcode' => 'nullable|string|max:255',
        ]);
        //}

        return $data_validation;
    }
}
