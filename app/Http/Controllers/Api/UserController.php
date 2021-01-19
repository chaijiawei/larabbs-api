<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function store(UserRequest $request)
    {
        $data = $request->validated();

        $verifyData = Cache::get($data['verify_key']);
        $code = $verifyData['code'];
        if(! $code) {
            abort(403, '验证码已经失效');
        }
        if(! hash_equals($code, $data['phone_code'])) {
            abort(401, '验证码错误');
        }
        if(User::query()
            ->where('phone', $verifyData['phone'])
            ->exists()
        ) {
           abort(403, '手机号码已经存在');
        }

        Cache::forget($data['verify_key']);

        $user = User::query()->create([
            'name' => $data['name'],
            'phone' => $verifyData['phone'],
            'password' => bcrypt($data['password']),
        ]);


        $token = Auth::guard('api')->login($user);
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
        ])->setStatusCode(201);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        $user->makeVisible(['phone', 'weixin_openid']);

        return new UserResource($user);
    }
}
