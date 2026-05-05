<x-layouts.portal :title="$post->seo_title ?: $post->title" :description="$post->seo_description ?: $post->excerpt" :image="$post->og_image ?: $post->featured_image">
    <article class="mx-auto max-w-4xl px-5 py-12">
        <header class="mb-10 text-center">
            <a href="{{ route('categories.show', $post->category) }}" class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-800">{{ $post->category->full_name }}</a>
            <h1 class="font-serif-news mt-4 text-4xl font-bold leading-tight text-stone-900 md:text-5xl lg:text-6xl">{{ $post->title }}</h1>

            <div class="mt-8 flex items-center justify-center gap-4 text-sm text-stone-500">
                <div class="flex flex-col items-center">
                    <span class="font-bold text-stone-900">{{ $post->author->name }}</span>
                    <span>{{ $post->published_at?->translatedFormat('l, d F Y') ?? $post->created_at->translatedFormat('l, d F Y') }}</span>
                </div>
            </div>
        </header>

        @if ($post->featured_image)
            @php
                $featuredImageCaption = $post->featured_image_caption;
            @endphp

            <figure class="mb-12">
                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="aspect-[16/9] w-full rounded-xl object-cover shadow-2xl shadow-stone-900/10">
                @if ($featuredImageCaption)
                    <figcaption class="mt-4 px-4 text-center text-sm italic text-stone-500">{{ $featuredImageCaption }}</figcaption>
                @endif
            </figure>
        @endif

        <div class="article-content font-serif-news prose prose-stone prose-lg mx-auto max-w-none leading-relaxed text-stone-800 text-lg md:text-xl">
            {!! $post->content !!}
        </div>

        <footer class="mt-12 border-t border-stone-100 pt-8">
            @if ($post->tags->isNotEmpty())
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-xs font-bold uppercase tracking-widest text-stone-400">Tagar:</span>
                    @foreach ($post->tags as $tag)
                        <span class="rounded-full bg-stone-100 px-4 py-1 text-sm font-semibold text-stone-600 transition-colors hover:bg-emerald-100 hover:text-emerald-800 cursor-default">#{{ $tag->name }}</span>
                    @endforeach
                </div>
            @endif

            @if ($post->unit)
                <div class="mt-8 flex items-center gap-4 rounded-2xl bg-emerald-50 p-6">
                    <div class="h-12 w-12 flex-shrink-0 rounded-full bg-emerald-800 grid place-items-center text-white font-bold text-xl">
                        {{ substr($post->unit->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-emerald-900/50">Unit Kerja</p>
                        <a href="{{ route('units.show', $post->unit) }}" class="text-lg font-bold text-emerald-900 hover:underline">{{ $post->unit->name }}</a>
                    </div>
                </div>
            @endif
        </footer>
    </article>

    @if ($relatedPosts->isNotEmpty())
        <section class="mx-auto max-w-7xl px-5 py-16">
            <div class="mb-8 flex items-end justify-between border-b-2 border-emerald-800 pb-3">
                <h2 class="font-serif-news text-3xl font-bold text-stone-900">Berita Terkait</h2>
                <div class="h-1 flex-1 ml-6 bg-stone-100 hidden md:block"></div>
            </div>
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($relatedPosts as $post)
                    <x-post-card :post="$post" />
                @endforeach
            </div>
        </section>
    @endif
</x-layouts.portal>
