<?php
namespace App\Http\Queries;

use App\Models\Reply;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ReplyQuery extends QueryBuilder
{
    public function __construct($subject = null)
    {
        if(is_null($subject)) {
            $subject = Reply::query();
        }
        parent::__construct($subject);

        $this->allowedIncludes('user', 'topic', 'topic.user')
            ->allowedFilters([
                AllowedFilter::exact('topic_id'),
                AllowedFilter::exact('user_id'),
            ]);
    }
}
