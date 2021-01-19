<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AvatarRequest extends FormRequest
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
            'avatar' => ['required', 'image',
                Rule::dimensions()->minWidth(200)->minHeight(200),
            ],
        ];
    }

    public function attributes()
    {
        return [
            'avatar' => '头像',
        ];
    }

    public function messages()
    {
        return [
            'avatar.dimensions' => '图片尺寸不能少于200x200',
        ];
    }
}
