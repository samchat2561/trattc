<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Banner extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = [
        'title', 'subtitle', 'button_text', 'button_url',
        'banner_type', 'position', 'is_active', 'starts_at', 'ends_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    // Register Media Collections (สำหรับ Spatie)
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('banner_image')->singleFile();
    }

    // Accessor สำหรับดึง URL รูปภาพเต็มตามพอร์ต/โดเมนการร้องขอปัจจุบัน
    public function getImageUrlAttribute(): string
    {
        $media = $this->getFirstMedia('banner_image');
        if ($media) {
            return asset('storage/' . $media->id . '/' . $media->file_name);
        }
        return '';
    }

    // Scope สำหรับดึงเฉพาะ Banner ที่เปิดใช้งานและอยู่ในช่วงเวลาที่กำหนด
    public function scopeActive(Builder $query): void
    {
        $now = now();
        $query->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->orderBy('position', 'asc');
    }
}
