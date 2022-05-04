<?php

namespace App\Http\Requests\V1;

use App\Http\Requests\BaseAPIRequest;

class RecordRequest extends BaseAPIRequest
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
            'begin' => 'required',
            'end' => 'required',
            'title' => 'max:128',
            'hospital' => 'nulable|integer',
            'people' => 'nulable|integer',
            'folder' => 'nulable|integer',
            'media' => 'nulable|integer',
        ];
    }

    public function messages()
    {
        return [
            'begin.required' => 'Begin is required!',
            'end.required' => 'End is required!',
        ];
    }
}
