<?php
namespace App\Http\Queries;

use App\Models\Topic;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class TopicQuery extends QueryBuilder
{
    public function __construct($subject = null)
    {
        if(is_null($subject)) {
            $subject = Topic::query();
        }
        parent::__construct($subject);

        $this->allowedIncludes('user', 'category')
            ->allowedFilters([
                'title',
                AllowedFilter::exact('category_id'),
                AllowedFilter::scope('with_order'),
            ]);
    }
}
