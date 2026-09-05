<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PhotoGalleryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'caption_description' => $this->caption_description,
            'image_url' => $this->image_path ? (str_starts_with($this->image_path, 'http') ? $this->image_path : asset('storage/'.$this->image_path)) : null,
            'location' => $this->location,
            'event_date' => $this->event_date?->format('Y-m-d'),
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ] : null,
            'unit' => $this->unit ? [
                'id' => $this->unit->id,
                'unit_name' => $this->unit->unit_name,
            ] : null,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
