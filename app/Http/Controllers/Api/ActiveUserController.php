<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class ActiveUserController extends Controller
{
    public function index(User $user)
    {
        return UserResource::collection($user->getActiveUserByCache());
    }
}
