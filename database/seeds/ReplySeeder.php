<?php

use Illuminate\Database\Seeder;
use App\Models\Reply;
use App\Models\User;
use App\Models\Topic;

class ReplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $replies = factory(Reply::class)->times(1000)->make();

        Reply::query()->insert($replies->toArray());

        //给用户1添加大量回复
        $user = User::query()->find(1);
        $topic = $user->topics()->withOrder('recentReply')->first();
        $replies_2 = factory(Reply::class)
                        ->times(100)
                        ->make()
                        ->each(function($reply) use ($topic) {
                            $reply->topic_id = $topic->id;
                        });
        Reply::query()->insert($replies_2->toArray());
        $topic->updateReplyCount();
    }
}
