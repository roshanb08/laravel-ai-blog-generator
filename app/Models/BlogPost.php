<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class BlogPost extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'meta_description',
        'content',
        'keywords',
        'og_title',
        'og_description',
        'og_image',
        'sources',
        'articles_used',
        'category',
        'country',
        'reading_time',
        'published_at',
    ];

    protected $casts = [
        'sources'      => 'array',
        'published_at' => 'datetime',
    ];

    // Splits the comma-separated keywords string into an array for view use
    public function getTagsAttribute(): array
    {
        if (empty($this->keywords)) {
            return [];
        }

        return array_values(array_filter(
            array_map('trim', explode(',', $this->keywords))
        ));
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
