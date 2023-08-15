<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
            'name'     => 'required|min:2',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $this->id, // id must be in POST
            'phone'    => 'nullable|string|max:255',
            'password' => 'required|min:8',
            'status'   => 'required|bool',
        ];
    }
}
