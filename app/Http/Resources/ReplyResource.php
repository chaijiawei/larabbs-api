<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReplyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = $this->resource->attributesToArray();

        $data['user'] = new UserResource($this->whenLoaded('user'));
        $data['topic'] = new TopicResource($this->whenLoaded('topic'));

        return $data;
    }
}
