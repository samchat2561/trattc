<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Staff extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'department_id',
        'position_id',
        'manager_id',
        'prefix',
        'first_name',
        'last_name',
        'email',
        'phone',
        'office',
        'biography',
        'sort_order',
        'is_active'
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'manager_id');
    }

    public function subordinates(): HasMany
    {
        return $this->hasMany(Staff::class, 'manager_id')->orderBy('sort_order');
    }

    // Accessor รวมชื่อ-นามสกุล
    public function getFullNameAttribute(): string
    {
        return "{$this->prefix}{$this->first_name} {$this->last_name}";
    }

    // ลงทะเบียน Media Collection สำหรับรูปโปรไฟล์
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile')->singleFile();
    }
}
