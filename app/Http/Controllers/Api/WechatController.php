<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Overtrue\LaravelSocialite\Socialite;

class WechatController extends Controller
{
    public function store()
    {
        $ouserInfo = Socialite::driver('wechat')->user();

        if(! $user = User::query()->where('weixin_openid', $ouserInfo->getId())->first()) {
            $user = User::query()->create([
                'name' => $ouserInfo->getNickname(),
                'avatar' => $ouserInfo->getAvatar(),
                'weixin_openid' => $ouserInfo->getId(),
            ]);
        }

        $token = Auth::guard('api')->login($user);
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
        ])->setStatusCode(201);
    }
}
