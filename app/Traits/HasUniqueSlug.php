<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUniqueSlug
{
    /**
     * Boot the trait and register the model saving event.
     */
    protected static function bootHasUniqueSlug(): void
    {
        static::saving(function ($model) {
            $slugSourceField = $model->slugSourceField ?? 'title';

            if (empty($model->slug) && ! empty($model->{$slugSourceField})) {
                $baseSlug = Str::slug($model->{$slugSourceField});
                $uniqueSuffix = strtolower(Str::random(5));
                $model->slug = "{$baseSlug}-{$uniqueSuffix}";
            }
        });
    }
}
