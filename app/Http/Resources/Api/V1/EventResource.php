<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'event_date' => $this->event_date?->format('Y-m-d'),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'location_venue' => $this->location_venue,
            'location_address' => $this->location_address,
            'organizer' => $this->organizer,
            'registration_link' => $this->registration_link,
            'event_status' => $this->event_status,
            'thumbnail_image_url' => $this->thumbnail_image ? (str_starts_with($this->thumbnail_image, 'http') ? $this->thumbnail_image : asset('storage/'.$this->thumbnail_image)) : null,
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
