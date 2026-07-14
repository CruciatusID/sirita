<?php

namespace App\Filament\Widgets;

use App\Models\PostView;
use Filament\Widgets\ChartWidget;

class CategoryViewsChart extends ChartWidget
{
    public ?string $month = null;
    public ?string $year = null;

    protected ?string $heading = 'Total Dibaca per Kategori (Bulan Ini)';

    protected function getData(): array
    {
        $month = $this->month ?? request()->query('month', now()->format('m'));
        $year = $this->year ?? request()->query('year', now()->format('Y'));

        $data = PostView::query()
            ->whereYear('post_views.created_at', $year)
            ->whereMonth('post_views.created_at', $month)
            ->join('posts', 'post_views.post_id', '=', 'posts.id')
            ->join('categories', 'posts.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, count(post_views.id) as views_count')
            ->groupBy('posts.category_id', 'categories.name')
            ->orderByDesc('views_count')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Dibaca',
                    'data' => $data->pluck('views_count')->map(fn ($views) => (int) $views)->toArray(),
                    'backgroundColor' => [
                        '#d97706', '#3b82f6', '#10b981', '#ef4444', '#8b5cf6',
                        '#ec4899', '#14b8a6', '#6366f1', '#a855f7', '#06b6d4'
                    ],
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
