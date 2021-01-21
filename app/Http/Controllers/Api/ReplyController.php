<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyRequest;
use App\Http\Resources\ReplyResource;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function store(ReplyRequest $request)
    {
        $data = $request->validated();

        $reply = $request->user()->replies()->create($data);

        return new ReplyResource($reply);
    }
}
