<x-layouts.portal title="SIRITA - Portal Berita Kemenag Tana Toraja">
    @if ($headline)
        <section class="mx-auto grid max-w-7xl gap-8 px-5 py-8 lg:grid-cols-[1.7fr_.9fr]">
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

            <aside class="space-y-6">
                <div class="bg-white p-6 shadow-sm ring-1 ring-stone-200">
                    <p class="text-xs font-bold uppercase tracking-widest text-emerald-800">Kategori Populer</p>
                    <div class="mt-5 flex flex-wrap gap-2">
                        @foreach ($categories->take(15) as $category)
                            <a href="{{ route('categories.show', $category) }}" class="rounded-full border border-stone-200 px-4 py-1.5 text-xs font-bold text-stone-600 transition-colors hover:border-emerald-600 hover:bg-emerald-50 hover:text-emerald-800">{{ $category->name }}</a>
                        @endforeach
                    </div>
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
        <div class="mb-8 flex items-end justify-between border-b-2 border-emerald-800 pb-3">
            <h2 class="font-serif-news text-3xl font-bold text-stone-900">Berita Terbaru</h2>
            <div class="h-1 flex-1 ml-6 bg-stone-100 hidden md:block"></div>
        </div>
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
    </section>
</x-layouts.portal>
