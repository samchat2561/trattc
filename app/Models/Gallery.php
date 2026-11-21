<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort',
        'image',
        'is_active',
    ];

    public function images()
    {
        return $this->belongsTo(Image::class, 'gallery_id');
    }
}
