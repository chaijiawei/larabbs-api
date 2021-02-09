<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JWTTokenController extends Controller
{
    public function update()
    {
        $token = Auth::guard('api')->refresh();

        return $this->responseWithToken($token)->setStatusCode(201);
    }

    public function destroy()
    {
        if(Auth::guard('api')->check()) {
            Auth::guard('api')->logout();
        }
        return response(null, 204);
    }
}
