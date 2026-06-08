<?php

namespace App\Filament\Pages;

use App\Filament\Support\AdminAccess;
use App\Models\Post;
use App\Models\User;
use Filament\Pages\Page;
use Livewire\Attributes\Url;

class NewsInsight extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    protected string $view = 'filament.pages.news-insight';

    protected static ?string $title = 'Insight & Analitik';

    protected static ?string $navigationLabel = 'Insight & Analitik';

    protected static ?int $navigationSort = 10;

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
            \App\Filament\Widgets\InsightStatsOverview::class,
            \App\Filament\Widgets\MonthlyPostsChart::class,
            \App\Filament\Widgets\CategoryViewsChart::class,
        ];
    }

    public function getViewData(): array
    {
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
        ];
    }
}
