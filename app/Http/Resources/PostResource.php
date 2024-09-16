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
            'User Id' => $this->user_id,
            'Group Id' => $this->group_id,
            'Title' => $this->title,
            'Description' => $this->description,
            'Image' => $this->image,
            'Likes' => $this->likes->count(),
            'comments' => $this->comments->count(),
            'Time' => $this->created_at->diffForHumans()
        ];
    }
}
