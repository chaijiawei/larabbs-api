<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AvatarRequest;
use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Service\ImageUpload;
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
        return $this->responseWithToken($token)->setStatusCode(201);
    }

    public function miniprogramStore(UserRequest $request)
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

        // 获取微信的 openid 和 session_key
        $miniProgram = \EasyWeChat::miniProgram();
        $miniProgramData = $miniProgram->auth->session($request->code);
        if(isset($miniProgramData['errcode'])) {
            abort(401, 'code 不正确');
        }

        if($user = User::query()
            ->where('miniprogram_openid', $miniProgramData['openid'])
            ->first()
        ) {
            abort(401, '微信已绑定其他用户，请直接登录');
        }

        $user = User::query()->create([
            'name' => $data['name'],
            'phone' => $verifyData['phone'],
            'password' => bcrypt($data['password']),
            'miniprogram_openid' => $miniProgramData['openid'],
            'miniprogram_session_key' => $miniProgramData['session_key'],
        ]);

        return new UserResource($user);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return (new UserResource($user))->showSensitiveField();
    }

    public function updateAvatar(AvatarRequest $request, ImageUpload $upload)
    {
        $data = $request->validated();
        $data['avatar'] = $upload->upload($data['avatar'], 'avatars', 320);
        $user = $request->user();
        $user->update([
            'avatar' => $data['avatar'],
        ]);

        return response()->json([
            'avatar' => $user->avatar,
        ])->setStatusCode(201);
    }

    public function update(UserRequest $request)
    {
        $data = $request->validated();

        $user = $request->user();
        $user->update($data);

        return (new UserResource($user))->showSensitiveField();
    }
}
