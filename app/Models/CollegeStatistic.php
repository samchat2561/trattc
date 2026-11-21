<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollegeStatistic extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'items' => 'array',
        'is_active' => 'boolean',
        'total_count' => 'integer',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function ($model) {
            if (is_array($model->items)) {
                $calculated = collect($model->items)->sum(fn ($i) => (int) ($i['value'] ?? 0));
                if ($calculated > 0 && ($model->total_count === 0 || empty($model->total_count))) {
                    $model->total_count = $calculated;
                }
            }
        });
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order', 'asc');
    }

    public function getChartDataAttribute(): array
    {
        $items = $this->items ?? [];
        $labels = [];
        $data = [];
        $colors = [];

        $defaultColors = [
            '#7a1a40', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6',
            '#ec4899', '#06b6d4', '#ea580c', '#64748b', '#14b8a6',
            '#6366f1', '#84cc16', '#a855f7', '#0284c7', '#d97706',
        ];

        foreach ($items as $index => $item) {
            $labels[] = $item['label'] ?? ('รายการที่ ' . ($index + 1));
            $data[] = (int) ($item['value'] ?? 0);
            $colors[] = !empty($item['color']) ? $item['color'] : ($defaultColors[$index % count($defaultColors)]);
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => $this->title,
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderColor' => '#ffffff',
                    'borderWidth' => 2,
                ],
            ],
        ];
    }
}
