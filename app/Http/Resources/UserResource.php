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
        $data = $this->resource->attributesToArray();
        $data['roles'] = RoleResource::collection($this->whenLoaded('roles'));

        return $data;
    }

    public function showSensitiveField()
    {
        $this->resource->makeVisible(['phone', 'weixin_openid']);

        return $this;
    }
}
