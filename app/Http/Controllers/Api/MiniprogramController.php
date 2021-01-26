<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MiniprogramRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MiniprogramController extends Controller
{
    public function store(MiniprogramRequest $request)
    {
        $app = \EasyWeChat::miniProgram();
        $data = $app->auth->session($request->code);
        if(! $user = User::query()->where('miniprogram_openid', $data['openid'])->first()) {
            $user = User::query()->create([
                'miniprogram_openid' => $data['openid'],
                'miniprogram_session_key' => $data['session_key'],
            ]);
        }

        $token = Auth::guard('api')->login($user);
        return $this->responseWithToken($token)->setStatusCode(201);
    }
}
