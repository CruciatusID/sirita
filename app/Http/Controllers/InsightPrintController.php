<?php

namespace App\Http\Controllers;

use App\Filament\Support\AdminAccess;
use App\Models\Post;
use App\Models\PostView;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InsightPrintController extends Controller
{
    public function __invoke(Request $request)
    {
        if (! AdminAccess::hasAnyRole(AdminAccess::EDITORIAL)) {
            abort(403);
        }

        $month = $request->query('month', now()->format('m'));
        $year = $request->query('year', now()->format('Y'));

        $referrers = PostView::query()
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->selectRaw('referrer, count(id) as views_count')
            ->groupBy('referrer')
            ->orderByDesc('views_count')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $item->referrer = $item->referrer ?: 'Direct / Akses Langsung';
                return $item;
            });

        $userAgents = PostView::query()
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
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

        $popularPosts = Post::query()
            ->withCount(['viewsRelation as monthly_views' => function ($query) use ($month, $year) {
                $query->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month);
            }])
            ->orderByDesc('monthly_views')
            ->with(['category', 'author'])
            ->limit(5)
            ->get();

        $topContributors = User::query()
            ->role('Kontributor')
            ->withCount([
                'posts as total_views' => function ($query) use ($month, $year) {
                    $query->join('post_views', 'posts.id', '=', 'post_views.post_id')
                        ->whereYear('post_views.created_at', $year)
                        ->whereMonth('post_views.created_at', $month);
                },
                'posts as published_posts_count' => function ($query) use ($month, $year) {
                    $query->where('status', 'published')
                        ->whereYear('published_at', $year)
                        ->whereMonth('published_at', $month);
                }
            ])
            ->orderByDesc('total_views')
            ->limit(5)
            ->get();

        $dateObj = Carbon::createFromDate((int) $year, (int) $month, 1);

        $monthlyViews = PostView::query()
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        $monthlyPosts = Post::query()
            ->where('status', 'published')
            ->whereYear('published_at', $year)
            ->whereMonth('published_at', $month)
            ->count();

        $avgViews = $monthlyPosts > 0 ? ($monthlyViews / $monthlyPosts) : 0;
        $allTimeViews = PostView::query()->count();

        return view('filament.pages.news-insight-print', compact(
            'month', 'year', 'dateObj',
            'monthlyViews', 'monthlyPosts', 'avgViews', 'allTimeViews',
            'popularPosts', 'topContributors', 'referrers', 'browsers'
        ));
    }
}
