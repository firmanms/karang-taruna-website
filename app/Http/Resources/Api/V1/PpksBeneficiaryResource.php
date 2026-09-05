<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PpksBeneficiaryResource extends JsonResource
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
            'nik' => $this->nik,
            'masked_nik' => $this->masked_nik,
            'full_name' => $this->full_name,
            'address_detail' => $this->address_detail,
            'social_assistance_status' => $this->social_assistance_status,
            'mentor_unit' => $this->mentor_unit,
            'last_survey_date' => $this->last_survey_date?->format('Y-m-d'),
            'verification_status' => $this->verification_status,
            'verification_notes' => $this->verification_notes,
            'verified_at' => $this->verified_at?->toIso8601String(),
            'category' => $this->category ? [
                'id' => $this->category->id,
                'category_code' => $this->category->category_code,
                'category_name' => $this->category->category_name,
            ] : null,
            'district' => $this->district ? [
                'id' => $this->district->id,
                'name' => $this->district->name,
                'kemendagri_code' => $this->district->kemendagri_code,
            ] : null,
            'village' => $this->village ? [
                'id' => $this->village->id,
                'name' => $this->village->name,
                'kemendagri_code' => $this->village->kemendagri_code,
            ] : null,
            'unit' => $this->unit ? [
                'id' => $this->unit->id,
                'unit_name' => $this->unit->unit_name,
                'unit_level' => $this->unit->unit_level,
            ] : null,
            'submitter' => $this->submitter ? [
                'id' => $this->submitter->id,
                'name' => $this->submitter->name,
            ] : null,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
