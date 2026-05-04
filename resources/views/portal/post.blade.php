<x-layouts.portal :title="$post->seo_title ?: $post->title" :description="$post->seo_description ?: $post->excerpt">
    <article class="mx-auto max-w-4xl px-5 py-12">
        <a href="{{ route('categories.show', $post->category) }}" class="text-xs font-bold uppercase tracking-[0.28em] text-emerald-800">{{ $post->category->name }}</a>
        <h1 class="mt-4 font-serif text-5xl font-black leading-none md:text-7xl">{{ $post->title }}</h1>
        <div class="mt-5 flex flex-wrap gap-3 text-sm text-stone-600">
            <span>{{ $post->published_at?->translatedFormat('d F Y H:i') ?? $post->created_at->translatedFormat('d F Y H:i') }}</span>
            <span>Ditulis oleh {{ $post->author->name }}</span>
            @if ($post->unit)
                <a href="{{ route('units.show', $post->unit) }}" class="font-bold text-emerald-800">{{ $post->unit->name }}</a>
            @endif
        </div>
        @if ($post->featured_image)
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="mt-8 max-h-[34rem] w-full rounded-[2rem] object-cover shadow-xl">
        @endif
        <div class="prose prose-stone mt-10 max-w-none text-lg leading-8">
            {!! $post->content !!}
        </div>
        @if ($post->tags->isNotEmpty())
            <div class="mt-10 flex flex-wrap gap-2">
                @foreach ($post->tags as $tag)
                    <span class="rounded-full bg-amber-100 px-4 py-2 text-sm font-bold">#{{ $tag->name }}</span>
                @endforeach
            </div>
        @endif
    </article>

    @if ($relatedPosts->isNotEmpty())
        <section class="mx-auto max-w-7xl px-5 py-8">
            <h2 class="mb-6 font-serif text-3xl font-black">Berita Terkait</h2>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($relatedPosts as $post)
                    <x-post-card :post="$post" />
                @endforeach
            </div>
        </section>
    @endif
</x-layouts.portal>
