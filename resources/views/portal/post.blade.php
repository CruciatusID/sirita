<x-layouts.portal :title="$post->seo_title ?: $post->title" :description="$post->seo_description ?: $post->excerpt" :image="$post->og_image ?: $post->featured_image">
    <article class="mx-auto max-w-5xl px-5 py-8">
        <div class="border-b border-stone-200 pb-6">
            <a href="{{ route('categories.show', $post->category) }}" class="text-xs font-bold uppercase tracking-wide text-emerald-800">{{ $post->category->full_name }}</a>
            <h1 class="mt-3 max-w-4xl text-4xl font-black leading-tight text-stone-950 md:text-5xl">{{ $post->title }}</h1>
        </div>

        <div class="mt-5 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-stone-600">
            <span>{{ $post->published_at?->translatedFormat('d F Y H:i') ?? $post->created_at->translatedFormat('d F Y H:i') }} WITA</span>
            <span>Oleh {{ $post->author->name }}</span>
            @if ($post->unit)
                <a href="{{ route('units.show', $post->unit) }}" class="font-bold text-emerald-800">{{ $post->unit->name }}</a>
            @endif
        </div>

        @if ($post->featured_image)
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="mt-6 aspect-[16/9] w-full object-cover">
        @endif

        <div class="article-content mt-8 max-w-none text-lg leading-8">
            {!! $post->content !!}
        </div>

        @if ($post->tags->isNotEmpty())
            <div class="mt-10 flex flex-wrap gap-2">
                @foreach ($post->tags as $tag)
                    <span class="bg-amber-100 px-3 py-1.5 text-sm font-bold">#{{ $tag->name }}</span>
                @endforeach
            </div>
        @endif
    </article>

    @if ($relatedPosts->isNotEmpty())
        <section class="mx-auto max-w-7xl px-5 py-8">
            <h2 class="mb-5 border-b border-stone-200 pb-3 text-2xl font-black">Berita Terkait</h2>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($relatedPosts as $post)
                    <x-post-card :post="$post" />
                @endforeach
            </div>
        </section>
    @endif
</x-layouts.portal>
