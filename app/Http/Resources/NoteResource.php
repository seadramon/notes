<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (is_null($this->resource)) {
            return [];
        }

        return [
            'id' => $this?->id,
            'title' => $this?->title,
            'content' => $this?->content,
            'created_by' => UserResource::make($this?->user),
            'remind_at' => [
                'value' => $this?->remind_at?->format('Y-m-d H:i'),
                'text' => $this?->remind_at?->isoFormat('DD MMMM YYYY HH:mm'),
            ],
            'created_at' => [
                'value' => $this?->created_at?->format('Y-m-d H:i'),
                'text' => $this?->created_at?->isoFormat('DD MMMM YYYY HH:mm'),
            ],
            'updated_at' => [
                'value' => $this?->updated_at?->format('Y-m-d H:i'),
                'text' => $this?->updated_at?->isoFormat('DD MMMM YYYY HH:mm'),
            ],
        ];
    }
}
