<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Mews\Captcha\Facades\Captcha;

class CaptchaController extends Controller
{
    public function store()
    {
        $data = Captcha::create('flat', true);
        if(app()->isLocal()) {
            $captcha = implode(Cache::get('captcha_record_' . $data['key']));
            $data = array_merge(['captcha' => $captcha], $data);
        }
        $data['expired_at']  = now()->addSeconds(config('captcha.flat.expire'))->toDateTimeString();

        return $data;
    }
}
