<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Achievement extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'achievements';

    protected $fillable = [
        'title',
        'position',
        'description',
        'facebook_url',
        'published_at',
        'views_count',
        'uploader_name',
        'user_id',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'published_at' => 'date',
        'is_active' => 'boolean',
        'views_count' => 'integer',
        'sort_order' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('poster')->singleFile();
    }

    public function getImageUrlAttribute(): string
    {
        $media = $this->getFirstMedia('poster');
        if ($media) {
            return asset('storage/' . $media->id . '/' . $media->file_name);
        }
        return asset('images/achievements/award1.jpg');
    }

    public function getFormattedDateAttribute(): string
    {
        if (!$this->published_at) {
            return '';
        }
        $year = $this->published_at->year + 543;
        return $this->published_at->format("d/m/{$year}");
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true)
              ->orderBy('sort_order', 'asc')
              ->orderBy('published_at', 'desc')
              ->orderBy('id', 'desc');
    }
}

