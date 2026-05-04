<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Unit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

class PortalController extends Controller
{
    public function home(): View
    {
        $headline = Post::published()
            ->with(['category', 'author'])
            ->latest('published_at')
            ->first();

        $latestPosts = Post::published()
            ->with(['category', 'author'])
            ->when($headline, fn ($query) => $query->whereKeyNot($headline->id))
            ->latest('published_at')
            ->paginate(9);

        return view('portal.home', [
            'headline' => $headline,
            'latestPosts' => $latestPosts,
            'categories' => Category::where('is_active', true)->orderBy('name')->get(),
            'banners' => Banner::where('status', 'active')->orderBy('order')->get(),
        ]);
    }

    public function post(Post $post): View
    {
        abort_unless($post->status === 'published' && ($post->published_at === null || $post->published_at->isPast()), 404);

        $post->increment('views');

        return view('portal.post', [
            'post' => $post->load(['category', 'author', 'unit', 'tags']),
            'relatedPosts' => Post::published()
                ->with('category')
                ->where('category_id', $post->category_id)
                ->whereKeyNot($post->id)
                ->latest('published_at')
                ->limit(4)
                ->get(),
        ]);
    }

    public function category(Category $category): View
    {
        return view('portal.archive', [
            'title' => "Kategori {$category->name}",
            'posts' => $category->posts()
                ->published()
                ->with(['category', 'author'])
                ->latest('published_at')
                ->paginate(12),
        ]);
    }

    public function unit(Unit $unit): View
    {
        return view('portal.archive', [
            'title' => "Unit {$unit->name}",
            'posts' => $unit->posts()
                ->published()
                ->with(['category', 'author'])
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
