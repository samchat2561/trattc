<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Position extends Model
{
    // เพิ่มบรรทัดนี้เพื่ออนุญาตให้บันทึกข้อมูลแบบ Mass Assignment ได้
    protected $fillable = [
        'name',
        'level',
        'sort_order',
        'color',
    ];

    /**
     * ความสัมพันธ์กับโมเดล Staff
     */
    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class)->orderBy('sort_order');
    }
}
