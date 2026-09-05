<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'featured_image_url' => $this->featured_image ? (str_starts_with($this->featured_image, 'http') ? $this->featured_image : asset('storage/'.$this->featured_image)) : null,
            'image_caption' => $this->image_caption,
            'news_scope' => $this->news_scope,
            'views_count' => $this->views_count,
            'is_featured' => (bool) $this->is_featured,
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ] : null,
            'unit' => $this->unit ? [
                'id' => $this->unit->id,
                'unit_name' => $this->unit->unit_name,
                'unit_level' => $this->unit->unit_level,
            ] : null,
            'district' => $this->district ? [
                'id' => $this->district->id,
                'name' => $this->district->name,
            ] : null,
            'village' => $this->village ? [
                'id' => $this->village->id,
                'name' => $this->village->name,
            ] : null,
            'published_at' => $this->published_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
