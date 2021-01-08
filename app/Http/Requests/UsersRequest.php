<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UsersRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255',
                Rule::unique('users')->ignore($this->user()),
            ],
            'avatar' => ['nullable', 'image',
                Rule::dimensions()->minWidth(200)->minHeight(200),
            ],
            'intro' => ['required', 'string', 'max:255']
        ];
    }

    public function attributes()
    {
        return [
            'name' => '用户名',
            'avatar' => '头像',
            'intro' => '个人简介'
        ];
    }

    public function messages()
    {
        return [
            'avatar.dimensions' => '图片尺寸不能少于200x200',
        ];
    }
}
