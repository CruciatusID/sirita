@props(['post'])

<article class="group flex flex-col overflow-hidden bg-white shadow-sm ring-1 ring-stone-200 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:ring-emerald-200">
    <a href="{{ route('posts.show', $post) }}" class="relative block aspect-[16/10] overflow-hidden bg-stone-100">
        @if ($post->featured_image)
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
        @else
            <div class="flex h-full w-full items-center justify-center bg-stone-100">
                <svg class="h-12 w-12 text-stone-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-stone-900/20 to-transparent opacity-0 transition-opacity group-hover:opacity-100"></div>
    </a>
    <div class="flex flex-1 flex-col p-5">
        <a href="{{ route('categories.show', $post->category) }}" class="text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-800">
            {{ $post->category->name }}
        </a>
        <h2 class="font-serif-news mt-2 text-xl font-bold leading-tight text-stone-900 group-hover:text-emerald-900">
            <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
        </h2>
        @if ($post->excerpt)
            <p class="mt-3 line-clamp-3 flex-1 text-sm leading-relaxed text-stone-600">
                {{ $post->excerpt }}
            </p>
        @endif
        <div class="mt-5 flex items-center justify-between border-t border-stone-100 pt-3">
            <span class="text-[10px] font-medium text-stone-400">
                {{ $post->published_at?->translatedFormat('d M Y') ?? $post->created_at->translatedFormat('d M Y') }}
            </span>
            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 opacity-0 transition-all group-hover:translate-x-1 group-hover:opacity-100">
                Baca Selengkapnya &rarr;
            </span>
        </div>
    </div>
</article>
