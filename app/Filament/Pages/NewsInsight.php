<?php

namespace App\Filament\Pages;

use App\Filament\Support\AdminAccess;
use App\Models\Post;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Livewire\Attributes\Url;

class NewsInsight extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    protected string $view = 'filament.pages.news-insight';

    protected static ?string $title = 'Insight & Analitik';

    protected static ?string $navigationLabel = 'Insight & Analitik';

    protected static ?int $navigationSort = 10;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print')
                ->label('Cetak Laporan (PDF)')
                ->icon('heroicon-o-printer')
                ->color('warning')
                ->extraAttributes([
                    'onclick' => 'window.print(); return false;',
                ]),
        ];
    }

    #[Url]
    public ?string $month = null;

    #[Url]
    public ?string $year = null;

    public static function canAccess(): bool
    {
        return AdminAccess::hasAnyRole(AdminAccess::EDITORIAL);
    }

    public function mount(): void
    {
        if (blank($this->month)) {
            $this->month = now()->format('m');
        }

        if (blank($this->year)) {
            $this->year = now()->format('Y');
        }
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\InsightStatsOverview::class => [
                'month' => $this->month,
                'year' => $this->year,
            ],
            \App\Filament\Widgets\MonthlyPostsChart::class => [
                'month' => $this->month,
                'year' => $this->year,
            ],
            \App\Filament\Widgets\CategoryViewsChart::class => [
                'month' => $this->month,
                'year' => $this->year,
            ],
        ];
    }

    public function getViewData(): array
    {
        // Ambil data referrers terpopuler pada bulan & tahun terpilih
        $referrers = \App\Models\PostView::query()
            ->whereYear('created_at', $this->year)
            ->whereMonth('created_at', $this->month)
            ->selectRaw('referrer, count(id) as views_count')
            ->groupBy('referrer')
            ->orderByDesc('views_count')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $item->referrer = $item->referrer ?: 'Direct / Akses Langsung';
                return $item;
            });

        // Olah data browser dari User Agent
        $userAgents = \App\Models\PostView::query()
            ->whereYear('created_at', $this->year)
            ->whereMonth('created_at', $this->month)
            ->select('user_agent')
            ->get();

        $browsersData = [
            'Chrome' => 0,
            'Safari' => 0,
            'Firefox' => 0,
            'Edge' => 0,
            'Opera' => 0,
            'Lainnya' => 0,
        ];

        foreach ($userAgents as $ua) {
            $agent = $ua->user_agent;
            if (empty($agent)) {
                $browsersData['Lainnya']++;
            } elseif (str_contains($agent, 'Edg')) {
                $browsersData['Edge']++;
            } elseif (str_contains($agent, 'Chrome')) {
                $browsersData['Chrome']++;
            } elseif (str_contains($agent, 'Safari')) {
                $browsersData['Safari']++;
            } elseif (str_contains($agent, 'Firefox')) {
                $browsersData['Firefox']++;
            } elseif (str_contains($agent, 'OPR') || str_contains($agent, 'Opera')) {
                $browsersData['Opera']++;
            } else {
                $browsersData['Lainnya']++;
            }
        }

        arsort($browsersData);
        $browsers = [];
        foreach ($browsersData as $name => $count) {
            if ($count > 0) {
                $browsers[] = (object) ['name' => $name, 'count' => $count];
            }
        }

        // 5 Popular Posts in the selected month & year
        $popularPosts = Post::query()
            ->withCount(['viewsRelation as monthly_views' => function ($query) {
                $query->whereYear('created_at', $this->year)
                    ->whereMonth('created_at', $this->month);
            }])
            ->orderByDesc('monthly_views')
            ->with(['category', 'author'])
            ->limit(5)
            ->get();

        // 5 Active Contributors (Authors) in the selected month & year based on total views of their posts
        $topContributors = User::query()
            ->role('Kontributor')
            ->withCount([
                'posts as total_views' => function ($query) {
                    $query->join('post_views', 'posts.id', '=', 'post_views.post_id')
                        ->whereYear('post_views.created_at', $this->year)
                        ->whereMonth('post_views.created_at', $this->month);
                },
                'posts as published_posts_count' => function ($query) {
                    $query->where('status', 'published')
                        ->whereYear('published_at', $this->year)
                        ->whereMonth('published_at', $this->month);
                }
            ])
            ->orderByDesc('total_views')
            ->limit(5)
            ->get();

        return [
            'popularPosts' => $popularPosts,
            'topContributors' => $topContributors,
            'referrers' => $referrers,
            'browsers' => $browsers,
        ];
    }
}
