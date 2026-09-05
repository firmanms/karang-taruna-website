<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitMemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'unit_id' => $this->unit_id,
            'full_name' => $this->full_name,
            'position_role' => $this->position_role,
            'division_section' => $this->division_section,
            'phone' => $this->phone,
            'email' => $this->email,
            'photo_url' => $this->photo_path ? (str_starts_with($this->photo_path, 'http') ? $this->photo_path : asset('storage/'.$this->photo_path)) : null,
            'order_index' => $this->order_index,
            'is_active' => (bool) $this->is_active,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
