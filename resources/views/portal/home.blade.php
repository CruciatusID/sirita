<x-layouts.portal title="SIRITA - Portal Berita Kemenag Tana Toraja">
    <section class="border-b border-emerald-900/10 bg-emerald-950 text-white">
        <div class="mx-auto grid max-w-7xl gap-3 px-5 py-3 lg:grid-cols-[minmax(0,1fr)_18rem] lg:items-center">
            @if ($popularCategories->isNotEmpty())
                <div class="flex min-w-0 items-center gap-4 overflow-hidden">
                    <div class="shrink-0 rounded-full bg-amber-300 px-3 py-1 text-[10px] font-black uppercase tracking-[0.2em] text-emerald-950">
                        Kategori Populer
                    </div>
                    <div class="popular-marquee min-w-0 flex-1 overflow-hidden">
                        <div class="popular-marquee-track flex w-max items-center gap-3">
                            @foreach ($popularCategories->concat($popularCategories) as $category)
                                <a href="{{ route('categories.show', $category) }}" class="whitespace-nowrap rounded-full border border-white/15 bg-white/10 px-4 py-1.5 text-xs font-bold text-emerald-50 transition-colors hover:bg-white hover:text-emerald-950">
                                    {{ $category->full_name }}
                                    <span class="ml-1 text-amber-200">{{ $category->published_posts_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-100">Portal Berita Kemenag Tana Toraja</div>
            @endif

            <form action="{{ route('search') }}" method="GET" class="flex overflow-hidden rounded-full bg-white/10 ring-1 ring-white/15 focus-within:bg-white focus-within:ring-amber-300">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari berita..." class="min-w-0 flex-1 bg-transparent px-4 py-2 text-sm text-white outline-none placeholder:text-emerald-100 focus:text-stone-900">
                <button type="submit" class="grid w-10 place-items-center bg-amber-300 text-emerald-950 transition-colors hover:bg-amber-200" aria-label="Cari berita">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                </button>
            </form>
        </div>
    </section>

    @if ($headline)
        <section class="mx-auto grid max-w-7xl gap-8 px-5 py-8 lg:grid-cols-[minmax(0,1fr)_22rem] lg:items-start">
            <article class="overflow-hidden bg-white shadow-sm ring-1 ring-stone-200">
                <a href="{{ route('posts.show', $headline) }}" class="block overflow-hidden bg-stone-100">
                    @if ($headline->featured_image)
                        <img src="{{ asset('storage/' . $headline->featured_image) }}" alt="{{ $headline->title }}" class="aspect-[16/9] w-full object-cover transition-transform duration-700 hover:scale-105">
                    @else
                        <div class="aspect-[16/9] w-full bg-stone-200"></div>
                    @endif
                </a>
                <div class="p-6 md:p-8">
                    <a href="{{ route('categories.show', $headline->category) }}" class="text-xs font-bold uppercase tracking-widest text-emerald-800">{{ $headline->category->full_name }}</a>
                    <h1 class="font-serif-news mt-4 text-3xl font-black leading-tight text-stone-950 md:text-5xl">
                        <a href="{{ route('posts.show', $headline) }}" class="hover:text-emerald-900 transition-colors">{{ $headline->title }}</a>
                    </h1>
                    @if ($headline->excerpt)
                        <p class="mt-5 max-w-3xl text-lg leading-relaxed text-stone-600">{{ $headline->excerpt }}</p>
                    @endif
                    <div class="mt-8 flex items-center gap-4 border-t border-stone-100 pt-6">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-stone-900">{{ $headline->author?->name ?? 'Redaksi' }}</span>
                            <span class="text-xs text-stone-500">{{ $headline->published_at?->translatedFormat('l, d F Y') ?? $headline->created_at->translatedFormat('l, d F Y') }}</span>
                        </div>
                    </div>
                </div>
            </article>

            <aside class="bg-white p-6 shadow-sm ring-1 ring-stone-200">
                <div class="border-b-2 border-emerald-800 pb-3">
                    <h2 class="font-serif-news text-2xl font-bold text-stone-900">Terpopuler 7 Hari</h2>
                    <p class="mt-2 text-xs leading-5 text-stone-500">Berdasarkan berita yang terbit 7 hari terakhir, diurutkan dari jumlah tayangan terbanyak.</p>
                </div>

                <div class="mt-5 grid gap-4">
                    @forelse ($popularPosts as $post)
                        <article class="group border-b border-stone-100 pb-4 last:border-b-0 last:pb-0">
                            <a href="{{ route('posts.show', $post) }}" class="font-serif-news text-lg font-bold leading-snug text-stone-900 group-hover:text-emerald-900">
                                {{ $post->title }}
                            </a>
                            <div class="mt-3 flex flex-wrap items-center gap-x-3 gap-y-1 text-[11px] font-semibold uppercase tracking-wider text-stone-400">
                                <span>{{ number_format($post->views) }} views</span>
                                <span>{{ number_format($post->likes_count) }} suka</span>
                                <span>{{ number_format($post->shares_count) }} share</span>
                            </div>
                        </article>
                    @empty
                        <p class="text-sm leading-6 text-stone-500">Belum ada berita terbit dalam 7 hari terakhir.</p>
                    @endforelse
                </div>
            </aside>
        </section>
    @else
        <section class="mx-auto max-w-7xl px-5 py-12">
            <div class="border border-stone-200 bg-white p-8">
                <p class="text-xs font-bold uppercase tracking-wide text-emerald-800">Portal Berita Resmi</p>
                <h1 class="mt-3 text-4xl font-black leading-tight">SIRITA Kemenag Tana Toraja</h1>
                <p class="mt-3 text-stone-600">Belum ada berita terbit.</p>
            </div>
        </section>
    @endif

    @if ($banners->isNotEmpty())
        <section class="mx-auto max-w-7xl px-5 py-4">
            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($banners as $banner)
                    <div class="overflow-hidden bg-white shadow-sm ring-1 ring-stone-200">
                        @if ($banner->link)
                            <a href="{{ $banner->link }}" target="_blank" rel="noopener noreferrer">
                                <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" class="w-full object-cover">
                            </a>
                        @else
                            <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" class="w-full object-cover">
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <section class="mx-auto max-w-7xl px-5 py-10">
        <div class="border-b-2 border-emerald-800 pb-3">
            <h2 class="font-serif-news text-3xl font-bold text-stone-900">Berita Terbaru</h2>
            <p class="mt-2 text-sm text-stone-500">Informasi terbaru dari Kemenag Tana Toraja.</p>
        </div>

        <div class="mt-8">
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($latestPosts as $post)
                    <x-post-card :post="$post" />
                @empty
                    <div class="col-span-full border-2 border-dashed border-stone-200 bg-stone-50 p-12 text-center text-stone-500">
                        Belum ada berita lain untuk ditampilkan.
                    </div>
                @endforelse
            </div>

            <div class="mt-12">{{ $latestPosts->links() }}</div>
        </div>
    </section>
</x-layouts.portal>
