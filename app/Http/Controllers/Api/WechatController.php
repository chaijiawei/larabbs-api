<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
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

        return $user;
    }
}
