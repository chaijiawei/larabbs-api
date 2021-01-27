<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MiniprogramRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use libphonenumber\PhoneNumber;

class MiniprogramController extends Controller
{
    public function store(MiniprogramRequest $request)
    {
        $app = \EasyWeChat::miniProgram();
        $data = $app->auth->session($request->code);
        if(! $user = User::query()->where('miniprogram_openid', $data['openid'])->first()) {
            if(! ($request->username && $request->password)) {
                abort(422, '请完善注册信息');
            }

            $userData = [
                'miniprogram_openid' => $data['openid'],
                'miniprogram_session_key' => $data['session_key'],
                'password' => bcrypt($request->password),
            ];

            if(filter_var($request->username, FILTER_VALIDATE_EMAIL)) { //邮箱
                if(User::query()->where('email', $request->username)->exists()) {
                    abort(422, '邮箱已经被注册了');
                }
                $userData['email'] = $request->username;
            } else { //手机
                $validator = Validator::make($request->all(), [
                    'username' => [
                        'required',
                        'regex:/^\d{11}$/',
                        'phone:CN,mobile',
                        Rule::unique('users', 'phone'),
                    ]
                ], [
                    'username.regex' => '请输入11位的电话号码',
                ], ['username' => '电话']);
                if($validator->fails()) {
                    throw new ValidationException($validator);
                }
                $userData['phone'] = $request->username;
            }

            $user = User::query()->create($userData);
        }

        $token = Auth::guard('api')->login($user);
        return $this->responseWithToken($token)->setStatusCode(201);
    }
}
