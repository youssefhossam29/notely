<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\NoteImageResource;

class NoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return[
            'slug' => $this->slug,
            'user_id' => $this->user_id,
            'user_name' => $this->user->name,
            'title' => $this->title,
            'content' => $this->content,
            'images' => NoteImageResource::collection($this->noteImages),
            'is_pinned' => $this->is_pinned ? 1 : 0,
            'deleted_at' => ($this->deleted_at == null ) ? null: $this->deleted_at->format('d/m/y'),
            'created_at' => $this->created_at->format('d/m/y'),
            'updated_at' => $this->updated_at->format('d/m/y'),
        ];
    }
}
