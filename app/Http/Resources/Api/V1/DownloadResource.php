<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DownloadResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'document_title' => $this->document_title,
            'description' => $this->description,
            'file_format' => $this->file_format,
            'file_size_kb' => $this->file_size_kb,
            'download_count' => $this->download_count,
            'release_date' => $this->release_date?->format('Y-m-d'),
            'download_url' => route('public.downloads.file', $this->id),
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ] : null,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
