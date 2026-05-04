<x-layouts.portal :title="$page->seo_title ?: $page->title" :description="$page->seo_description">
    <article class="mx-auto max-w-4xl px-5 py-12">
        <p class="text-xs font-bold uppercase tracking-[0.28em] text-emerald-800">Halaman Informasi</p>
        <h1 class="mt-4 font-serif text-5xl font-black leading-none md:text-7xl">{{ $page->title }}</h1>
        <div class="prose prose-stone mt-10 max-w-none text-lg leading-8">
            {!! $page->content !!}
        </div>
    </article>
</x-layouts.portal>
