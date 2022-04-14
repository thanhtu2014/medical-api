<?php

namespace App\Http\Requests\V1;

use App\Http\Requests\BaseAPIRequest;

class PeopleRequest extends BaseAPIRequest
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
            'user' => 'max:128',
            'post' => 'max:8',
            'pref' => 'max:24',
            'pref_code' => 'max:24',
            'address' => 'min:6|max:1024',
            'xaddress' => 'min:6|max:1024',
            'note' => 'min:3|max:1024',
            'phone' => 'min:10|max:128',
            'mail' => 'email|min:6|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required!',
            'name.min' => 'Name must be at least 3 characters!',
            'address.min' => 'Address must be at least 6 characters!',
            'address.max' => 'Address must be at least 1024 characters!',
            'note.min' => 'Note must be at least 3 characters!',
            'note.max' => 'Note must be at least 1024 characters!',
        ];
    }
}
