<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Id' => $this->id,
            'UserId' => $this->user_id,
            'GroupId' => $this->group_id,
            'Title' => $this->title,
            'Description' => $this->description,
            'Image' => $this->image,
            'Likes' => $this->likes_count,
            'comments' => $this->comments_count,
            'Time' => $this->created_at->diffForHumans()
        ];
    }
}
