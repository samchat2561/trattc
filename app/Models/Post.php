<?php

namespace App\Models;

use App\PostStatus;
use App\PostType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Tags\HasTags;

class Post extends Model
{
    use HasTags;

    protected $guarded = [];

    protected $casts = [
        'status' => PostStatus::class,
        'type' => PostType::class,
        'meta_keywords' => 'json',
        'attachments' => 'array',
        'published_at' => 'datetime',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_posts');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function scopePost($query): Builder
    {
        return $query->where('type', PostType::POST);
    }

    public function scopePage($query): Builder
    {
        return $query->where('type', PostType::PAGE);
    }

    public function scopePublished($query): Builder
    {
        return $query->where('status', PostStatus::PUBLISHED);
    }

    /**
     * Accessor สำหรับ feature_image (singular) ให้ส่งกลับเป็น Array เสมอเพื่อรองรับ Filament FileUpload
     */
    public function getFeatureImageAttribute($value): array
    {
        if (empty($value)) {
            return [];
        }

        if (str_starts_with($value, '[') && str_ends_with($value, ']')) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        return [$value];
    }

    /**
     * Mutator สำหรับ feature_image (singular) ให้แปลงค่าอาร์เรย์เป็น JSON string ก่อนบันทึก
     */
    public function setFeatureImageAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['feature_image'] = json_encode(array_values($value));
        } else {
            $this->attributes['feature_image'] = $value;
        }
    }

    /**
     * ดึงลิสต์ของรูปภาพ Feature Image ทั้งหมด (ส่งค่าอาร์เรย์ของรูปภาพกลับไป)
     */
    public function getFeatureImagesAttribute(): array
    {
        return $this->feature_image;
    }
}
