<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        if(Str::startsWith($this->profile->image, 'https://')){
            $image = $this->profile->image;
        }else{
            $image = 'uploads/users/' .$this->profile->image;
        }

        return[
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'image' => $image,
            'bio' => $this->profile->bio,
            'city' => $this->profile->city,
            'gender' => $this->gender_type,
            'created_at' => $this->created_at->format('d/m/y'),
            'updated_at' => $this->updated_at->format('d/m/y'),
        ];    }
}
