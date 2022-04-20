<?php

namespace App\Http\Requests\V1;

use App\Http\Requests\BaseAPIRequest;

class ScheduleRequest extends BaseAPIRequest
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
            'title' => 'required|min:3|max:128',
            'date' => 'required',
            'color'=> 'required|min:3|max:6',
            'hospital' => 'required',
            'people' => 'required',
            'remark' => 'min:3|max:1024',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Title is required!',
            'date.required' => 'DateTime is required!',
            'color.required' => 'Color is required!',
            'hospital.required' => 'Hospital is required!',
            'people.required' => 'Doctor is required!',
            'remark.min' => 'Note must be at least 3 characters!',
            'remark.max' => 'Note must be at least 1024 characters!',
        ];
    }
}
