@props(['post'])

<article class="group overflow-hidden border border-stone-200 bg-white">
    <a href="{{ route('posts.show', $post) }}" class="block bg-stone-200">
        @if ($post->featured_image)
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="aspect-[16/10] w-full object-cover transition group-hover:scale-[1.02]">
        @else
            <div class="aspect-[16/10] w-full bg-stone-200"></div>
        @endif
    </a>
    <div class="space-y-3 p-4">
        <a href="{{ route('categories.show', $post->category) }}" class="text-xs font-bold uppercase tracking-wide text-emerald-700">
            {{ $post->category->full_name }}
        </a>
        <h2 class="text-xl font-black leading-snug text-stone-950">
            <a href="{{ route('posts.show', $post) }}" class="group-hover:text-emerald-800">{{ $post->title }}</a>
        </h2>
        @if ($post->excerpt)
            <p class="line-clamp-3 text-sm leading-6 text-stone-600">{{ $post->excerpt }}</p>
        @endif
        <p class="text-xs text-stone-500">
            {{ $post->published_at?->translatedFormat('d F Y') ?? $post->created_at->translatedFormat('d F Y') }}
        </p>
    </div>
</article>
