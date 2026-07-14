<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\PostView;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InsightStatsOverview extends StatsOverviewWidget
{
    public ?string $month = null;
    public ?string $year = null;

    protected function getStats(): array
    {
        $month = $this->month ?? request()->query('month', now()->format('m'));
        $year = $this->year ?? request()->query('year', now()->format('Y'));

        $dateObj = \Carbon\Carbon::createFromDate((int) $year, (int) $month, 1);
        $periodLabel = $dateObj->translatedFormat('F Y');

        // 1. Total Views in Selected Month
        $monthlyViews = PostView::query()
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        // 2. Published Posts in Selected Month
        $monthlyPosts = Post::query()
            ->where('status', 'published')
            ->whereYear('published_at', $year)
            ->whereMonth('published_at', $month)
            ->count();

        // 3. Average Views per Post in Selected Month
        $avgViews = $monthlyPosts > 0 ? ($monthlyViews / $monthlyPosts) : 0;

        // 4. All-time Views
        $allTimeViews = PostView::query()->count();

        return [
            Stat::make("Pembaca ($periodLabel)", number_format($monthlyViews))
                ->description('Total tayangan berita')
                ->icon(Heroicon::OutlinedEye)
                ->color('amber'),
            Stat::make("Berita Terbit ($periodLabel)", number_format($monthlyPosts))
                ->description('Jumlah berita dipublikasikan')
                ->icon(Heroicon::OutlinedNewspaper)
                ->color('success'),
            Stat::make("Rata-rata Dibaca ($periodLabel)", number_format($avgViews, 1))
                ->description('Rasio tayangan per berita')
                ->icon(Heroicon::OutlinedPresentationChartLine)
                ->color('info'),
            Stat::make('Total Pembaca (Semua Waktu)', number_format($allTimeViews))
                ->description('Akumulasi sepanjang sejarah')
                ->icon(Heroicon::OutlinedGlobeAlt)
                ->color('gray'),
        ];
    }
}
