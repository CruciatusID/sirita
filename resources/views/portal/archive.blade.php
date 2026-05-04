<x-layouts.portal :title="$title . ' - SIRITA'">
    <section class="mx-auto max-w-7xl px-5 py-12">
        <p class="text-xs font-bold uppercase tracking-[0.28em] text-emerald-800">Arsip Berita</p>
        <h1 class="mt-4 font-serif text-5xl font-black">{{ $title }}</h1>
        <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($posts as $post)
                <x-post-card :post="$post" />
            @empty
                <p class="rounded-3xl bg-white p-8 text-stone-600 shadow-sm">Belum ada berita terbit.</p>
            @endforelse
        </div>
        <div class="mt-8">{{ $posts->links() }}</div>
    </section>
</x-layouts.portal>
