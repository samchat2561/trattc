<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    // เพิ่มบรรทัดนี้เพื่อให้สามารถบันทึก user_id ได้
    protected $fillable = [
        'user_id',
    ];

    public function likeable()
    {
        return $this->morphTo();
    }
    
    // ความสัมพันธ์ย้อนกลับไปยัง User (แนะนำให้มีไว้)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}