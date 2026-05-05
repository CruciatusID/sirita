<x-layouts.portal :title="filled($query) ? 'Hasil pencarian ' . $query . ' - SIRITA' : 'Pencarian - SIRITA'">
    <section class="mx-auto max-w-7xl px-5 py-12">
        <p class="text-xs font-bold uppercase tracking-[0.28em] text-emerald-800">Pencarian</p>
        <h1 class="mt-4 font-serif-news text-4xl font-black text-stone-950 md:text-5xl">
            @if (filled($query))
                Hasil untuk "{{ $query }}"
            @else
                Cari Berita
            @endif
        </h1>

        <form action="{{ route('search') }}" method="GET" class="mt-8 flex max-w-2xl overflow-hidden rounded-full bg-white shadow-sm ring-1 ring-stone-200 focus-within:ring-2 focus-within:ring-emerald-700">
            <input type="search" name="q" value="{{ $query }}" placeholder="Cari judul, isi, atau kategori..." class="min-w-0 flex-1 border-0 bg-transparent px-5 py-3 text-sm text-stone-800 outline-none placeholder:text-stone-400">
            <button type="submit" class="grid w-12 place-items-center bg-emerald-800 text-white transition-colors hover:bg-emerald-900" aria-label="Cari">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.35-4.35" />
                </svg>
            </button>
        </form>

        <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($posts as $post)
                <x-post-card :post="$post" />
            @empty
                <div class="col-span-full border-2 border-dashed border-stone-200 bg-stone-50 p-12 text-center text-stone-500">
                    Tidak ada berita yang cocok.
                </div>
            @endforelse
        </div>

        <div class="mt-8">{{ $posts->links() }}</div>
    </section>
</x-layouts.portal>
