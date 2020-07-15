<?php

namespace App\Http\Resources\CRM;

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
        $resource = [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email
        ];

        if ($this->whenLoaded('friends')) {
            $resource = array_merge($resource, [
                //'friends' => new FriendCollection($this->acceptedFriends()->paginate(10))
            ]);
        }

        return $resource;
    }
}
