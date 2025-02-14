<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CommentForCommentResource;
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
            'UserId' => $this->user_id,
            'PostId' => $this->post_id,
            'Description' => $this->description,
            "NumberOfReplies" => $this->replies_count,
            "Replies" => CommentForCommentResource::collection($this->replies),
            'Time' => $this->created_at->diffForHumans()
        ];
    }
}
