<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TerritoryDistrictResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kemendagri_code' => $this->kemendagri_code,
            'name' => $this->name,
            'slug' => $this->slug,
            'latitude_center' => $this->latitude_center,
            'longitude_center' => $this->longitude_center,
            'villages_count' => $this->villages_count ?? $this->villages()->count(),
            'units_count' => $this->units_count ?? $this->units()->count(),
        ];
    }
}
