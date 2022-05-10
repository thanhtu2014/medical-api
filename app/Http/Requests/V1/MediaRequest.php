<?php

namespace App\Http\Requests\V1;

use App\Http\Requests\BaseAPIRequest;

class MediaRequest extends BaseAPIRequest
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
            'file' =>'nullable|mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav,m4a'
        ];
    }

    public function messages()
    {
        return [
            'file.mimes' => 'wrong file extension',
        ];
    }
}
