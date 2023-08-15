<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogArticleRequest extends FormRequest
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
            'main_category_id'                    => 'required',
            'order'                               => 'required|integer',
        ];
    }

    public function messages()
    {
        $defaultLang = config('translatable.locale');

        return [
            'page_data.' . $defaultLang . '.name.required' => __('Title is a required field'),
            'page_data.' . $defaultLang . '.name.min'      => __('Title must be at least 2 characters'),
        ];
    }
}
