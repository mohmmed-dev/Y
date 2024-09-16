<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
// use App\Resource\CommentForCommentResource;
class CommentResource extends JsonResource
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
            'Post Id' => $this->post_id,
            'Description' => $this->description,
            "Comments" => $this->comments->count(),
            'Time' => $this->created_at->diffForHumans()
        ];
    }
}
