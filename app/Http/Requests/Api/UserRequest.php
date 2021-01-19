<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $rules = [];
        switch($this->method()) {
            case 'POST':
                $rules = [
                    'name' => ['bail', 'required', 'string', 'max:255', 'unique:users'],
                    'verify_key' => ['required', 'string'],
                    'phone_code' => ['required', 'string'],
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
                ];
                break;
            case 'PATCH':
                $rules = [
                    'name' => ['required', 'string', 'max:255',
                        Rule::unique('users')->ignore($this->user()),
                    ],
                    'intro' => ['required', 'string', 'max:255'],
                ];
                break;
            default:
                break;
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'phone_code' => '短信验证码',
            'name' => '用户名',
            'intro' => '个人简介',
        ];
    }

}
