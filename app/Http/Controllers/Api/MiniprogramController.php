<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MiniprogramRequest;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MiniprogramController extends Controller
{
    public function store(MiniprogramRequest $request)
    {
        $app = \EasyWeChat::miniProgram();
        $data = $app->auth->session($request->code);
        if(! $user = User::query()->where('miniprogram_openid', $data['openid'])->first()) {
            $userData = [
                'miniprogram_openid' => $data['openid'],
                'miniprogram_session_key' => $data['session_key'],
            ];

            $credentials = ['password' => $request->password];
            if(filter_var($request->username, FILTER_VALIDATE_EMAIL)) { //邮箱
                $credentials['email'] = $request->username;
            } else { //手机
                $credentials['phone'] = $request->username;
            }

            if(! Auth::guard('api')->once($credentials)) {
                throw new AuthenticationException('用户名或者密码错误');
            }

            $user = Auth::guard('api')->getUser();
            $user->update($userData);
        }

        $token = Auth::guard('api')->login($user);
        return $this->responseWithToken($token)->setStatusCode(201);
    }
}
