<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder; // แก้ไข: ใช้ Builder ของ Eloquent
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'guest_name',
        'guest_email',
        'is_approved',
        'body',
    ];

    // เพิ่ม relationship สำหรับ User (ถ้ายังไม่มี)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    // ปรับปรุงฟังก์ชันเช็ค Like ให้รับเป็น ID เพื่อลดการส่ง Object ขนาดใหญ่
    public function isLikedBy($userId)
    {
        if (! $userId) {
            return false;
        }

        return $this->likes->where('user_id', $userId)->isNotEmpty();
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('replies');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}
