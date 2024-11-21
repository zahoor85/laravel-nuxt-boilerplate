<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'full_name' => $this->full_name,
            'gender' => $this->gender,
            'hobbies' => json_decode($this->hobbies),
            'dob' => $this->dob,
            'profile_picture' => $this->profile_picture ? url('storage/profile/thumbnail/t_' . $this->profile_picture) : null,
        ];
    }
}
