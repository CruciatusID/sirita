<x-layouts.portal title="SIRITA - Portal Berita Kemenag Tana Toraja">
    @if ($headline)
        <section class="mx-auto grid max-w-7xl gap-8 px-5 py-8 lg:grid-cols-[1.7fr_.9fr]">
            <article class="overflow-hidden border border-stone-200 bg-white">
                <a href="{{ route('posts.show', $headline) }}" class="block bg-stone-200">
                    @if ($headline->featured_image)
                        <img src="{{ asset('storage/' . $headline->featured_image) }}" alt="{{ $headline->title }}" class="aspect-[16/9] w-full object-cover">
                    @else
                        <div class="aspect-[16/9] w-full bg-stone-200"></div>
                    @endif
                </a>
                <div class="p-6 md:p-8">
                    <a href="{{ route('categories.show', $headline->category) }}" class="text-xs font-bold uppercase tracking-wide text-emerald-800">{{ $headline->category->full_name }}</a>
                    <h1 class="mt-3 text-3xl font-black leading-tight text-stone-950 md:text-5xl">
                        <a href="{{ route('posts.show', $headline) }}">{{ $headline->title }}</a>
                    </h1>
                    @if ($headline->excerpt)
                        <p class="mt-4 max-w-3xl leading-7 text-stone-600">{{ $headline->excerpt }}</p>
                    @endif
                    <p class="mt-5 text-sm text-stone-500">{{ $headline->published_at?->translatedFormat('d F Y H:i') ?? $headline->created_at->translatedFormat('d F Y H:i') }} WITA</p>
                </div>
            </article>

            <aside class="border border-stone-200 bg-white p-5">
                <p class="text-xs font-bold uppercase tracking-wide text-emerald-800">Kategori</p>
                <div class="mt-4 grid gap-2">
                    @foreach ($categories->take(12) as $category)
                        <a href="{{ route('categories.show', $category) }}" class="border border-stone-200 px-3 py-2 text-sm font-semibold text-stone-700 hover:border-emerald-700 hover:text-emerald-800">{{ $category->full_name }}</a>
                    @endforeach
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

    <section class="mx-auto max-w-7xl px-5 py-10">
        <div class="mb-5 flex items-end justify-between border-b border-stone-200 pb-3">
            <h2 class="text-2xl font-black">Berita Terbaru</h2>
        </div>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($latestPosts as $post)
                <x-post-card :post="$post" />
            @empty
                <p class="border border-stone-200 bg-white p-6 text-stone-600">Belum ada berita lain.</p>
            @endforelse
        </div>
        <div class="mt-8">{{ $latestPosts->links() }}</div>
    </section>
</x-layouts.portal>
