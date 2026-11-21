<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// ⚡ 1. ต้องนำเข้าคลาสจัดการมีเดีย 2 ตัวนี้
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

// ⚡ 2. ต้องทำการ implements HasMedia ด้านหลังคลาสด้วยครับ
class Department extends Model implements HasMedia
{
    // ⚡ 3. ต้องเรียกใช้ Trait นี้ภายในตัวคลาส
    use InteractsWithMedia;

    protected $guarded = [];

    // ⚡ 4. ลงทะเบียนมีเดียคอลเลกชัน 'profile' เพื่อบังคับให้เก็บรูปเดี่ยว (Single File)
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile')
            ->singleFile(); // บังคับให้ 1 แผนกมีรูปผู้บริหารได้แค่รูปเดียว
    }

    protected $fillable = ['parent_id', 'name', 'code', 'description', 'sort_order', 'status'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Department::class, 'parent_id')->orderBy('sort_order');
    }

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class)->orderBy('sort_order');
    }
}
