<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        if ($this->profile->gender === null) {
            $gender = 'not selected';
        } elseif ($this->profile->gender == 1) {
            $gender = 'male';
        } else {
            $gender = 'female';
        }

        return[
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'image' => 'uploads/users/' .$this->profile->image,
            'bio' => $this->profile->bio,
            'city' => $this->profile->city,
            'gender' => $gender,
            'created_at' => $this->created_at->format('d/m/y'),
            'updated_at' => $this->updated_at->format('d/m/y'),
        ];    }
}
