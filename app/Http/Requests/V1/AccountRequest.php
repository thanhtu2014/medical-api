<?php

namespace App\Http\Requests\V1;

use App\Http\Requests\BaseAPIRequest;

class AccountRequest extends BaseAPIRequest
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
            'type' => 'min:3|max:24',
            'email' => 'email|max:255',
            'name' => 'min:3|max:128',
            'gender' => 'min:3|max:24', 
            'temail' => 'email|max:255',
            'remark' => 'min:3|max:1024',
        ];
    }

    public function messages()
    {
        return [
            'name.min' => 'Name must be at least 3 characters!',
            'gender.min' => 'Name must be at least 3 characters!',
            'remark.min' => 'Remark must be at least 3 characters!',
            'remark.max' => 'Remark must be at least 1024 characters!',
        ];
    }
}
