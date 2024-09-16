<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\GroupResource;

class UserResource extends JsonResource
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
            "Full Name" => $this->name,
            "E-mail" => $this->email,
            "Image" => $this->image,
            "Groups" => $this->groups->count(),
            "Member" => $this->members->count()
        ];
    }
}
