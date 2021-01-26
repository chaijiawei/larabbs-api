<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function store(LoginRequest $request)
    {
        $data = $request->validated();
        $where = [];
        if(filter_var($data['username'], FILTER_VALIDATE_EMAIL)) {
            $where['email'] = $data['username'];
        } else {
            $where['phone'] = $data['username'];
        }
        $where['password'] = $data['password'];
        if(! $token = Auth::guard('api')->attempt($where)) {
            throw new AuthenticationException('用户名或密码错误');
        }

        return $this->responseWithToken($token)->setStatusCode(201);
    }
}
