<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkProgramResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'program_name' => $this->program_name,
            'slug' => $this->slug,
            'description' => $this->description,
            'objective' => $this->objective,
            'target_participants' => $this->target_participants,
            'output_indicators' => $this->output_indicators,
            'budget_amount' => (float) $this->budget_amount,
            'execution_time' => $this->execution_time,
            'progress_status' => $this->progress_status,
            'banner_image_url' => $this->banner_image ? (str_starts_with($this->banner_image, 'http') ? $this->banner_image : asset('storage/'.$this->banner_image)) : null,
            'division' => $this->division ? [
                'id' => $this->division->id,
                'division_name' => $this->division->division_name,
            ] : null,
            'unit' => $this->unit ? [
                'id' => $this->unit->id,
                'unit_name' => $this->unit->unit_name,
            ] : null,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
