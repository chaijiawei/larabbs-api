<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TopicsRequest;
use App\Http\Resources\TopicResource;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function store(TopicsRequest $request)
    {
        $data = $request->validated();

        $topic = $request->user()->topics()->create($data);

        return new TopicResource($topic);
    }
}
