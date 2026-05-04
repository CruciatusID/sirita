<x-layouts.portal title="SIRITA - Portal Berita Kemenag Tana Toraja">
    <section class="mx-auto grid max-w-7xl gap-8 px-5 py-10 lg:grid-cols-[1.5fr_.8fr]">
        <div class="rounded-[2rem] bg-stone-950 p-6 text-white shadow-2xl md:p-10">
            <p class="text-xs font-bold uppercase tracking-[0.3em] text-amber-200">Portal Berita Resmi</p>
            <h1 class="mt-5 max-w-3xl font-serif text-5xl font-black leading-none md:text-7xl">Informasi Religi Kemenag Tana Toraja.</h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-stone-300">Publikasi kegiatan kantor, KUA, madrasah, bimas, penyuluh, dan layanan informasi publik.</p>
        </div>

        <aside class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-emerald-800">Kategori</p>
            <div class="mt-5 flex flex-wrap gap-2">
                @foreach ($categories as $category)
                    <a href="{{ route('categories.show', $category) }}" class="rounded-full bg-amber-100 px-4 py-2 text-sm font-bold text-stone-800 hover:bg-emerald-800 hover:text-white">{{ $category->name }}</a>
                @endforeach
            </div>
        </aside>
    </section>

    @if ($headline)
        <section class="mx-auto max-w-7xl px-5">
            <article class="grid overflow-hidden rounded-[2rem] bg-white shadow-xl lg:grid-cols-2">
                @if ($headline->featured_image)
                    <img src="{{ asset('storage/' . $headline->featured_image) }}" alt="{{ $headline->title }}" class="h-full min-h-96 w-full object-cover">
                @endif
                <div class="flex flex-col justify-center p-8 md:p-12">
                    <p class="text-xs font-bold uppercase tracking-[0.3em] text-emerald-700">Headline</p>
                    <h2 class="mt-4 font-serif text-4xl font-black leading-tight md:text-5xl">
                        <a href="{{ route('posts.show', $headline) }}">{{ $headline->title }}</a>
                    </h2>
                    <p class="mt-5 leading-7 text-stone-600">{{ $headline->excerpt }}</p>
                    <a href="{{ route('posts.show', $headline) }}" class="mt-8 inline-flex w-fit rounded-full bg-stone-950 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-800">Baca Berita</a>
                </div>
            </article>
        </section>
    @endif

    <section class="mx-auto max-w-7xl px-5 py-12">
        <div class="mb-6 flex items-end justify-between">
            <h2 class="font-serif text-4xl font-black">Berita Terbaru</h2>
        </div>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($latestPosts as $post)
                <x-post-card :post="$post" />
            @endforeach
        </div>
        <div class="mt-8">{{ $latestPosts->links() }}</div>
    </section>
</x-layouts.portal>
