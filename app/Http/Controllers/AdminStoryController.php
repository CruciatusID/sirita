<?php

namespace App\Http\Controllers;

use App\Filament\Support\AdminAccess;
use App\Models\Post;
use Illuminate\Contracts\View\View;

class AdminStoryController extends Controller
{
    public function post(Post $post): View
    {
        abort_unless($post->status === 'published' && filled($post->slug), 404);
        abort_unless($this->canAccessPost($post), 403);

        return view('admin.story.post', [
            'post' => $post->load(['category.parent', 'author']),
            'postUrl' => route('posts.show', $post),
            'templateUrl' => asset('images/story/ig-story-template-empty.png'),
            'imageUrl' => filled($post->featured_image) ? asset('storage/'.$post->featured_image) : null,
        ]);
    }

    private function canAccessPost(Post $post): bool
    {
        if (AdminAccess::hasAnyRole(AdminAccess::CONTENT_MANAGERS)) {
            return true;
        }

        if (AdminAccess::hasAnyRole(['Editor'])) {
            return $post->status !== 'draft';
        }

        return (int) $post->user_id === (int) auth()->id();
    }
}
