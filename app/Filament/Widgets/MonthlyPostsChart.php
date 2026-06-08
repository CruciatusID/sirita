<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class MonthlyPostsChart extends ChartWidget
{
    protected ?string $heading = 'Tren Publikasi Berita (6 Bulan Terakhir)';

    protected function getData(): array
    {
        $month = request()->query('month', now()->format('m'));
        $year = request()->query('year', now()->format('Y'));

        // Start from the selected month & year
        $startDate = Carbon::createFromDate((int) $year, (int) $month, 1);

        $data = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = (clone $startDate)->subMonths($i);
            $y = $date->year;
            $m = $date->month;

            $count = Post::query()
                ->where('status', 'published')
                ->whereYear('published_at', $y)
                ->whereMonth('published_at', $m)
                ->count();

            $data[] = $count;
            $labels[] = $date->translatedFormat('F Y');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Berita Diterbitkan',
                    'data' => $data,
                    'borderColor' => '#d97706', // Amber-600
                    'backgroundColor' => 'rgba(217, 119, 6, 0.1)',
                    'fill' => 'start',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
