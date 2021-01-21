<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Queries\ReplyQuery;
use App\Http\Requests\ReplyRequest;
use App\Http\Resources\ReplyResource;
use App\Models\Reply;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function store(ReplyRequest $request)
    {
        $data = $request->validated();

        $reply = $request->user()->replies()->create($data);

        return new ReplyResource($reply->attributesToArray());
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('destroy', $reply);
        $reply->delete();

        return response(null, 204);
    }

    public function index(ReplyQuery $query)
    {
        $replies = $query->paginate();

        return ReplyResource::collection($replies);
    }
}
