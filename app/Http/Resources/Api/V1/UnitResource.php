<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'unit_code' => $this->unit_code,
            'unit_name' => $this->unit_name,
            'unit_level' => $this->unit_level,
            'chairman_name' => $this->chairman_name,
            'secretary_name' => $this->secretary_name,
            'treasurer_name' => $this->treasurer_name,
            'contact_phone' => $this->contact_phone,
            'contact_email' => $this->contact_email,
            'office_address' => $this->office_address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'period_start_year' => $this->period_start_year,
            'period_end_year' => $this->period_end_year,
            'total_members' => $this->total_members,
            'status_aktif' => $this->status_aktif,
            'district' => $this->district ? [
                'id' => $this->district->id,
                'name' => $this->district->name,
                'kemendagri_code' => $this->district->kemendagri_code,
            ] : null,
            'village' => $this->village ? [
                'id' => $this->village->id,
                'name' => $this->village->name,
            ] : null,
        ];
    }
}
