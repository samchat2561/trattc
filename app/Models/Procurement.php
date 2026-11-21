<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Procurement extends Model
{
    protected $table = 'procurements';

    protected $fillable = [
        'title',
        'procurement_type',
        'budget',
        'description',
        'published_at',
        'deadline_at',
        'status',
        'views_count',
        'uploader_name',
        'user_id',
        'image',
        'attachments',
        'link_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'published_at' => 'date',
        'deadline_at' => 'date',
        'is_active' => 'boolean',
        'views_count' => 'integer',
        'sort_order' => 'integer',
        'attachments' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }
        return asset('images/procurements/procurement_default.svg');
    }

    public function getFormattedDateAttribute(): string
    {
        if (!$this->published_at) {
            return '';
        }
        $year = $this->published_at->year + 543;
        return $this->published_at->format("d/m/{$year}");
    }

    public function getFormattedDeadlineAttribute(): string
    {
        if (!$this->deadline_at) {
            return '';
        }
        $year = $this->deadline_at->year + 543;
        return $this->deadline_at->format("d/m/{$year}");
    }

    public function getAttachmentFilesAttribute(): array
    {
        if (empty($this->attachments) || !is_array($this->attachments)) {
            return [];
        }

        $files = [];
        foreach ($this->attachments as $filePath) {
            if (!$filePath) {
                continue;
            }

            $name = basename($filePath);
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $fullPath = public_path('storage/' . $filePath);

            $sizeStr = '';
            if (file_exists($fullPath)) {
                $bytes = filesize($fullPath);
                $sizeStr = $this->formatBytes($bytes);
            }

            $iconData = $this->getFileIconData($ext);

            $files[] = [
                'name' => $name,
                'path' => $filePath,
                'url' => asset('storage/' . $filePath),
                'ext' => strtoupper($ext),
                'size' => $sizeStr,
                'icon' => $iconData['icon'],
                'color' => $iconData['color'],
                'bg_color' => $iconData['bg_color'],
            ];
        }

        return $files;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'closed' => 'สิ้นสุดรับข้อเสนอ',
            'awarded' => 'ประกาศผลผู้ชนะแล้ว',
            default => 'กำลังเปิดรับข้อเสนอ',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'closed' => 'bg-slate-500 text-white',
            'awarded' => 'bg-blue-600 text-white',
            default => 'bg-emerald-600 text-white',
        };
    }

    protected function formatBytes(int $bytes, int $precision = 1): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    protected function getFileIconData(string $ext): array
    {
        return match ($ext) {
            'pdf' => [
                'icon' => 'fas fa-file-pdf',
                'color' => 'text-red-500',
                'bg_color' => 'bg-red-50 text-red-600 border-red-200 dark:bg-red-950/40 dark:text-red-400 dark:border-red-800',
            ],
            'doc', 'docx' => [
                'icon' => 'fas fa-file-word',
                'color' => 'text-blue-600',
                'bg_color' => 'bg-blue-50 text-blue-600 border-blue-200 dark:bg-blue-950/40 dark:text-blue-400 dark:border-blue-800',
            ],
            'xls', 'xlsx' => [
                'icon' => 'fas fa-file-excel',
                'color' => 'text-emerald-600',
                'bg_color' => 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-800',
            ],
            'ppt', 'pptx' => [
                'icon' => 'fas fa-file-powerpoint',
                'color' => 'text-orange-500',
                'bg_color' => 'bg-orange-50 text-orange-600 border-orange-200 dark:bg-orange-950/40 dark:text-orange-400 dark:border-orange-800',
            ],
            'zip', 'rar', '7z' => [
                'icon' => 'fas fa-file-zipper',
                'color' => 'text-amber-600',
                'bg_color' => 'bg-amber-50 text-amber-600 border-amber-200 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-800',
            ],
            default => [
                'icon' => 'fas fa-file-lines',
                'color' => 'text-slate-500',
                'bg_color' => 'bg-slate-50 text-slate-600 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
            ],
        };
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true)
              ->orderBy('sort_order', 'asc')
              ->orderBy('published_at', 'desc')
              ->orderBy('id', 'desc');
    }
}
