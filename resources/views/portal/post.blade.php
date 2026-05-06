<x-layouts.portal :title="$post->seo_title ?: $post->title" :description="$post->seo_description ?: $post->excerpt" :image="$post->og_image ?: $post->featured_image">
    <article class="mx-auto max-w-4xl px-5 py-12">
        <header class="mb-10 text-center">
            <a href="{{ route('categories.show', $post->category) }}" class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-800">{{ $post->category->full_name }}</a>
            <h1 class="font-serif-news mt-4 text-4xl font-bold leading-tight text-stone-900 md:text-5xl lg:text-6xl">{{ $post->title }}</h1>

            <div class="mt-8 space-y-1 text-sm leading-6 text-stone-600">
                <p>
                    <span class="font-bold text-stone-900">Tayang:</span>
                    {{ $post->published_at?->translatedFormat('l, d F Y H:i') ?? $post->created_at->translatedFormat('l, d F Y H:i') }} WITA
                </p>
                <p>
                    <span class="font-bold text-stone-900">Penulis:</span>
                    {{ $post->author->name }}
                    @if ($post->editor)
                        <span class="mx-2 text-stone-400">|</span>
                        <span class="font-bold text-stone-900">Editor:</span>
                        {{ $post->editor->name }}
                    @endif
                </p>
            </div>

            <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                <span class="rounded-full bg-stone-100 px-4 py-2 text-xs font-bold text-stone-600">
                    {{ number_format($post->views) }} views
                </span>
                <span class="rounded-full bg-stone-100 px-4 py-2 text-xs font-bold text-stone-600">
                    {{ number_format($post->likes_count) }} suka
                </span>
                <span class="rounded-full bg-stone-100 px-4 py-2 text-xs font-bold text-stone-600">
                    <span data-share-count>{{ number_format($post->shares_count) }}</span> dibagikan
                </span>
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
            <div class="mb-8 flex flex-wrap items-center justify-end gap-3">
                <div class="flex gap-3">
                    <form method="POST" action="{{ route('posts.like', $post) }}">
                        @csrf
                        <button type="submit" class="grid h-11 w-11 place-items-center rounded-full bg-emerald-800 text-white shadow-lg shadow-emerald-900/15 transition-all hover:-translate-y-0.5 hover:bg-emerald-900" title="{{ session()->has("liked_posts.{$post->id}") ? 'Sudah disukai' : 'Suka' }}" aria-label="{{ session()->has("liked_posts.{$post->id}") ? 'Sudah disukai' : 'Suka berita ini' }}">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="{{ session()->has("liked_posts.{$post->id}") ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M7 10v11" />
                                <path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2h0a3.13 3.13 0 0 1 3 3.88Z" />
                            </svg>
                        </button>
                    </form>

                    <a href="{{ route('posts.share.whatsapp', $post) }}" target="_blank" rel="noopener noreferrer" class="grid h-11 w-11 place-items-center rounded-full bg-[#25D366] text-white shadow-lg shadow-emerald-900/10 transition-all hover:-translate-y-0.5 hover:bg-[#1ebe5d]" title="Bagikan ke WhatsApp" aria-label="Bagikan berita ini ke WhatsApp">
                        <svg class="h-5 w-5" viewBox="0 0 32 32" fill="currentColor" aria-hidden="true">
                            <path d="M16.01 3.2A12.66 12.66 0 0 0 5.02 22.13L3.2 28.8l6.83-1.79A12.66 12.66 0 1 0 16.01 3.2Zm0 23.1a10.5 10.5 0 0 1-5.36-1.47l-.38-.23-4.05 1.06 1.08-3.94-.25-.4A10.5 10.5 0 1 1 16 26.3Zm5.75-7.86c-.31-.16-1.84-.91-2.13-1.01-.29-.11-.5-.16-.71.16-.21.31-.82 1.01-1.01 1.22-.18.21-.37.24-.68.08-.31-.16-1.32-.49-2.51-1.55-.93-.83-1.56-1.86-1.74-2.17-.18-.31-.02-.48.14-.64.14-.14.31-.37.47-.55.16-.18.21-.31.31-.52.11-.21.05-.39-.03-.55-.08-.16-.71-1.71-.97-2.34-.26-.62-.52-.53-.71-.54h-.6c-.21 0-.55.08-.84.39-.29.31-1.1 1.08-1.1 2.62s1.13 3.04 1.29 3.25c.16.21 2.22 3.39 5.38 4.75.75.32 1.34.52 1.8.66.76.24 1.45.21 1.99.13.61-.09 1.84-.75 2.1-1.48.26-.73.26-1.36.18-1.49-.08-.13-.29-.21-.61-.37Z" />
                        </svg>
                    </a>

                    <a href="{{ route('posts.share.facebook', $post) }}" target="_blank" rel="noopener noreferrer" class="grid h-11 w-11 place-items-center rounded-full bg-[#1877F2] text-white shadow-lg shadow-blue-900/10 transition-all hover:-translate-y-0.5 hover:bg-[#166fe5]" title="Bagikan ke Facebook" aria-label="Bagikan berita ini ke Facebook">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M14 8.5h2.5V5.1c-.43-.06-1.9-.19-3.62-.19-3.58 0-6.03 2.25-6.03 6.38v3.8H3v3.8h3.85V24h4.72v-5.11h3.69l.59-3.8h-4.28v-3.42c0-1.1.3-1.85 1.89-1.85H16V8.5h-2Z" />
                        </svg>
                    </a>
                </div>
            </div>

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
