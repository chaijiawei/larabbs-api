<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TopicsRequest;
use App\Http\Resources\TopicResource;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function store(TopicsRequest $request)
    {
        $data = $request->validated();

        $topic = $request->user()->topics()->create($data);

        return new TopicResource($topic);
    }

    public function update(Topic $topic, TopicsRequest $request)
    {
        $this->authorize('update', $topic);

        $data = $request->validated();
        $topic->update($data);

        return new TopicResource($topic);
    }

    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);

        $topic->delete();

        return response(null, 204);
    }
}
