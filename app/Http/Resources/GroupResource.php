<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "Id" => $this->id,
            "Owner Id" => $this->user_id,
            "Name" => $this->name,
            "Description" => $this->description,
            "Image" => $this->image,
            "Public" => $this->public,
            "Members" => $this->members->count(),
            "Posts" => $this->posts->count()
        ];
    }
}
