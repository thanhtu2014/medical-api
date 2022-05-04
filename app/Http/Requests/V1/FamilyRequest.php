<?php

namespace App\Http\Requests\V1;

use App\Http\Requests\BaseAPIRequest;

class FamilyRequest extends BaseAPIRequest
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
            'name' => 'required|min:3|max:128',
            'email' => 'required|email|max:255|unique:peoples,email',
            'remark' => 'min:3|max:1024',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required!',
            'name.min' => 'Name must be at least 3 characters!',
            'remark.min' => 'Remark must be at least 3 characters!',
            'remark.max' => 'Remark must be at least 1024 characters!',
        ];
    }
}
