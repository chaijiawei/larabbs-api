<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->attributesToArray();
    }

    public function showSensitiveField()
    {
        $this->resource->makeVisible(['phone', 'weixin_openid']);

        return $this;
    }
}
