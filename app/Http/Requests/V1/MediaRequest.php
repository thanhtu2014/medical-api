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
            'fpath' =>'nullable|file|mimes:audio/mpeg,mpga,mp3,wav,aac,m4a'
        ];
    }

    public function messages()
    {
        return [
            'fpath.mimes' => 'wrong file extension',
        ];
    }
}
