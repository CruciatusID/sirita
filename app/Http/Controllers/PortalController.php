<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Unit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PortalController extends Controller
{
    public function home(): View
    {
        $headline = Post::published()
            ->with(['category.parent', 'author'])
            ->latest('published_at')
            ->first();

        $latestPosts = Post::published()
            ->with(['category.parent', 'author'])
            ->when($headline, fn ($query) => $query->whereKeyNot($headline->id))
            ->latest('published_at')
            ->paginate(9);

        $popularCategories = Category::where('is_active', true)
            ->with('parent')
            ->whereHas('posts', fn ($query) => $query->published())
            ->withCount([
                'posts as published_posts_count' => fn ($query) => $query->published(),
            ])
            ->orderByDesc('published_posts_count')
            ->orderBy('name')
            ->limit(12)
            ->get();

        $popularPosts = Post::published()
            ->with(['category.parent', 'author'])
            ->where('published_at', '>=', now()->subDays(7))
            ->orderByDesc('views')
            ->latest('published_at')
            ->limit(5)
            ->get();

        return view('portal.home', [
            'headline' => $headline,
            'latestPosts' => $latestPosts,
            'categories' => Category::where('is_active', true)->with('parent')->orderBy('name')->get(),
            'popularCategories' => $popularCategories,
            'popularPosts' => $popularPosts,
            'banners' => Banner::where('status', 'active')->orderBy('order')->get(),
        ]);
    }

    public function post(Post $post): View
    {
        abort_unless($post->status === 'published' && ($post->published_at === null || $post->published_at->isPast()), 404);

        $post->increment('views');

        return view('portal.post', [
            'post' => $post->load(['category.parent', 'author', 'editor', 'unit', 'tags']),
            'relatedPosts' => Post::published()
                ->with('category.parent')
                ->where('category_id', $post->category_id)
                ->whereKeyNot($post->id)
                ->latest('published_at')
                ->limit(4)
                ->get(),
        ]);
    }

    public function like(Post $post): RedirectResponse
    {
        abort_unless($post->status === 'published' && ($post->published_at === null || $post->published_at->isPast()), 404);

        $sessionKey = "liked_posts.{$post->id}";

        if (! session()->has($sessionKey)) {
            $post->increment('likes_count');
            session()->put($sessionKey, true);
        }

        return back();
    }

    public function share(Request $request, Post $post): JsonResponse|RedirectResponse
    {
        abort_unless($post->status === 'published' && ($post->published_at === null || $post->published_at->isPast()), 404);

        $post->increment('shares_count');

        if ($request->expectsJson()) {
            return response()->json([
                'shares_count' => $post->shares_count,
            ]);
        }

        return back();
    }

    public function category(Category $category): View
    {
        return view('portal.archive', [
            'title' => "Kategori {$category->name}",
            'posts' => $category->posts()
                ->published()
                ->with(['category.parent', 'author'])
                ->latest('published_at')
                ->paginate(12),
        ]);
    }

    public function search(Request $request): View
    {
        $query = trim((string) $request->query('q'));

        $posts = Post::published()
            ->with(['category.parent', 'author'])
            ->when($query !== '', function ($builder) use ($query): void {
                $builder->where(function ($builder) use ($query): void {
                    $builder
                        ->where('title', 'like', "%{$query}%")
                        ->orWhere('excerpt', 'like', "%{$query}%")
                        ->orWhere('content', 'like', "%{$query}%")
                        ->orWhereHas('category', fn ($category) => $category->where('name', 'like', "%{$query}%"));
                });
            })
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('portal.search', [
            'query' => $query,
            'posts' => $posts,
        ]);
    }

    public function unit(Unit $unit): View
    {
        return view('portal.archive', [
            'title' => "Unit {$unit->name}",
            'posts' => $unit->posts()
                ->published()
                ->with(['category.parent', 'author'])
                ->latest('published_at')
                ->paginate(12),
        ]);
    }

    public function page(Page $page): View
    {
        abort_unless($page->status === 'published', 404);

        return view('portal.page', ['page' => $page]);
    }

    public function sitemap(): Response
    {
        $posts = Post::published()->latest('published_at')->get(['slug', 'updated_at']);
        $pages = Page::where('status', 'published')->get(['slug', 'updated_at']);
        $categories = Category::where('is_active', true)->get(['slug', 'updated_at']);

        return response()
            ->view('portal.sitemap', compact('posts', 'pages', 'categories'))
            ->header('Content-Type', 'application/xml');
    }
}
