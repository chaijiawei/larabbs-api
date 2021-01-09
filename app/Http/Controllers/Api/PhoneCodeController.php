<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PhoneCodeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PhoneCodeController extends Controller
{
    public function store(PhoneCodeRequest $request)
    {
        $data = $request->validated();

        $phone = $data['phone'];
        $code = Str::random(4);
        $key = "{$phone}_" . Str::random(10);
    }
}
