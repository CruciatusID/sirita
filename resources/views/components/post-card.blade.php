@props(['post'])

<article class="group overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
    @if ($post->featured_image)
        <a href="{{ route('posts.show', $post) }}">
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="h-52 w-full object-cover">
        </a>
    @endif
    <div class="space-y-3 p-5">
        <a href="{{ route('categories.show', $post->category) }}" class="text-xs font-bold uppercase tracking-[0.24em] text-emerald-700">
            {{ $post->category->name }}
        </a>
        <h2 class="font-serif text-2xl font-black leading-tight text-stone-950">
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
