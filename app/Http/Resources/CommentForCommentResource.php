<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentForCommentResource extends JsonResource
{
    /**
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Id' => $this->id,
            'User Id' => $this->user_id,
            'Comment Id' => $this->comment_id,
            'Description' => $this->description,
            'Time' => $this->created_at->diffForHumans()
        ];
    }
}
