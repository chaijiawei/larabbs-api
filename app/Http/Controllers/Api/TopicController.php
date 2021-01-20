<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TopicsRequest;
use App\Http\Resources\TopicResource;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

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

    public function index()
    {
        $topics = QueryBuilder::for(Topic::class)
            ->allowedIncludes('user', 'category')
            ->allowedFilters([
                'title',
                AllowedFilter::exact('category_id'),
                AllowedFilter::scope('with_order'),
            ])
            ->paginate();

        return TopicResource::collection($topics);
    }

    public function userIndex(User $user)
    {
        $query = $user->topics();

        $topics = QueryBuilder::for($query)
            ->allowedIncludes('user', 'category')
            ->allowedFilters([
                'title',
                AllowedFilter::exact('category_id'),
                AllowedFilter::scope('with_order'),
            ])
            ->paginate();

        return TopicResource::collection($topics);
    }
}
