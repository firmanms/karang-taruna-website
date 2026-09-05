<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AchievementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'recipient_name' => $this->recipient_name,
            'achievement_level' => $this->achievement_level,
            'category_field' => $this->category_field,
            'year' => $this->year,
            'rank_position' => $this->rank_position,
            'awarded_by' => $this->awarded_by,
            'description' => $this->description,
            'certificate_image_url' => $this->certificate_image ? (str_starts_with($this->certificate_image, 'http') ? $this->certificate_image : asset('storage/'.$this->certificate_image)) : null,
            'unit' => $this->unit ? [
                'id' => $this->unit->id,
                'unit_name' => $this->unit->unit_name,
            ] : null,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
