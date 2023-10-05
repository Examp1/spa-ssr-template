<?php

namespace Owlwebdev\Ecom\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|',
            'phone' => 'string|min:1|max:255',
            'name' => 'required|string|min:1|max:255',
            'lastname' => 'required|string|min:1|max:255',
            'company' => 'nullable|string|max:255',
            'country' => 'required|string|min:1|max:255',
            'province' => 'nullable|string|min:1|max:255',
            'city' => 'required|string|min:1|max:255',
            'postcode' => 'nullable|string|min:1|max:255',
            'address' => 'required|string|min:1|max:500',
            'apartment' => 'nullable|string|max:255',

            'changed_email' => 'nullable|email',

            'shipping_different_address' => 'nullable|boolean',

            'shipping_method' => 'required',
            'shipping_email' => 'nullable|email',
            'shipping_phone' => 'nullable|string|max:255',
            'shipping_name' => 'nullable|string|max:255',
            'shipping_lastname' => 'nullable|string|max:255',
            'shipping_country' => 'nullable|string|max:255',
            'shipping_province' => 'nullable|string|max:255',
            'shipping_city' => 'nullable|string|max:255',
            'shipping_postcode' => 'nullable|string|max:255',
            'shipping_address' => 'nullable|string|max:255',
            'shipping_apartment' => 'nullable|string|max:255',

            'billing_shipping_address' => 'nullable|boolean',

            'payment_method' => 'required',

            'billing_email' => 'nullable|email',
            'billing_phone' => 'nullable|string|max:255',
            'billing_name' => 'nullable|string|max:255',
            'billing_lastname' => 'nullable|string|max:255',
            'billing_country' => 'nullable|string|max:255',
            'billing_province' => 'nullable|string|max:255',
            'billing_city' => 'nullable|string|max:255',
            'billing_address' => 'nullable|string|max:255',
            'billing_postcode' => 'nullable|string|max:255',
            'billing_apartment' => 'nullable|string|max:255',

            'coupon' => 'nullable|string|max:255',
        ];
    }
}
