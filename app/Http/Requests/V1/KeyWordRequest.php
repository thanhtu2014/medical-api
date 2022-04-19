<?php

namespace App\Http\Requests\V1;

use App\Http\Requests\BaseAPIRequest;

class KeyWordRequest extends BaseAPIRequest
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
            'image' => 'image|mimes:jpg,jpeg,png|max:2048',
            'name' => 'min:3|max:128',
            'type' => 'min:3|max:24',
            'color' => 'max:128',
            'user' => 'max:128',
            'vx01' => 'max:128',
            'vx02' => 'max:128',
            'remark' => 'min:3|max:1024'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required!',
            'name.min' => 'Name must be at least 3 characters!',
            'remark.min' => 'Remark must be at least 6 characters!',
            'remark.max' => 'Remark must be at least 1024 characters!',
        ];
    }
}
