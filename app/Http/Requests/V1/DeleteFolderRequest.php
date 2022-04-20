<?php

namespace App\Http\Requests\V1;

use App\Http\Requests\BaseAPIRequest;

class DeleteFolderRequest extends BaseAPIRequest
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
            'color' => 'required|min:6|max:6',
            'pid' => 'nullable|numeric',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required!',
            'name.min' => 'Name must be at least 3 characters!',
            'color.required' => 'Color is required!',
            'color.min' => 'Color must be 6 character!',
            'color.max' => 'Color must be 6 character!',
            'pid.numeric' => 'Only number!',
        ];
    }
}
