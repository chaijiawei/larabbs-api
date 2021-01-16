<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PhoneCodeRequest extends FormRequest
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
            'captcha' => [
                'required',
                'captcha_api:' . $this->captcha_key . ',flat',
            ],
            'phone' => [
                'bail',
                'required',
                'regex:/^\d{11}$/',
                'phone:CN,mobile',
                Rule::unique('users'),
            ],
        ];
    }

    public function messages()
    {
        return [
            'phone.regex' => '请输入11位的电话号码',
            'captcha_api' => '验证码错误',
        ];
    }
}
