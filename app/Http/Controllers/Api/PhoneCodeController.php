<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PhoneCodeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PhoneCodeController extends Controller
{
    public function store(PhoneCodeRequest $request)
    {
        $data = $request->validated();


        $phone = $data['phone'];
        $code = str_pad(random_int(0, 9999), 4, 0, STR_PAD_LEFT);
        $key = "{$phone}_" . Str::random(10);
        $expireAt = now()->addMinutes(5);
        if(app()->isLocal()) {
            $expireAt = now()->addYear();
        }
        Cache::put($key, ['code' => $code, 'phone' => $phone], $expireAt);

        //发送手机短信

        $data = [
            'phone' => $phone,
            'key' => $key,
            'expired_at' => $expireAt->toDateTimeString(),
        ];
        if(app()->isLocal()) {
            $data['code'] = $code;
        }

        return $data;
    }
}
