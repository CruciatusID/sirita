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
                <p class="hidden rounded-full bg-emerald-50 px-4 py-2 text-xs font-bold text-emerald-800" data-share-status role="status" aria-live="polite"></p>

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

                    <form method="POST" action="{{ route('posts.share', $post) }}" data-share-form data-share-title="{{ $post->title }}" data-share-url="{{ route('posts.show', $post) }}">
                        @csrf
                        <button type="submit" class="grid h-11 w-11 place-items-center rounded-full border border-stone-200 bg-white text-stone-700 shadow-lg shadow-stone-900/5 transition-all hover:-translate-y-0.5 hover:border-emerald-700 hover:text-emerald-800" title="Bagikan" aria-label="Bagikan berita ini">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="18" cy="5" r="3" />
                                <circle cx="6" cy="12" r="3" />
                                <circle cx="18" cy="19" r="3" />
                                <path d="m8.59 13.51 6.83 3.98" />
                                <path d="m15.41 6.51-6.82 3.98" />
                            </svg>
                        </button>
                    </form>
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

    <script>
        function copyTextToClipboard(text) {
            if (navigator.clipboard && window.isSecureContext) {
                return navigator.clipboard.writeText(text);
            }

            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.setAttribute('readonly', '');
            textArea.style.position = 'fixed';
            textArea.style.opacity = '0';
            document.body.appendChild(textArea);
            textArea.select();

            try {
                document.execCommand('copy');

                return Promise.resolve();
            } catch (error) {
                return Promise.reject(error);
            } finally {
                document.body.removeChild(textArea);
            }
        }

        function showShareStatus(message) {
            const status = document.querySelector('[data-share-status]');

            if (! status) {
                return;
            }

            status.textContent = message;
            status.classList.remove('hidden');

            window.clearTimeout(status.dataset.timeoutId);
            status.dataset.timeoutId = window.setTimeout(() => {
                status.classList.add('hidden');
                status.textContent = '';
            }, 2500);
        }

        document.querySelectorAll('[data-share-form]').forEach((form) => {
            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const shareUrl = form.dataset.shareUrl;
                const shareTitle = form.dataset.shareTitle;
                const token = document.querySelector('meta[name="csrf-token"]')?.content;

                try {
                    if (navigator.share) {
                        await navigator.share({ title: shareTitle, url: shareUrl });
                        showShareStatus('Dialog bagikan dibuka');
                    } else {
                        await copyTextToClipboard(shareUrl);
                        showShareStatus('Link berita disalin');
                    }

                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token,
                        },
                    });

                    if (response.ok) {
                        const data = await response.json();
                        const counter = document.querySelector('[data-share-count]');

                        if (counter && data.shares_count !== undefined) {
                            counter.textContent = new Intl.NumberFormat('id-ID').format(data.shares_count);
                        }
                    }
                } catch (error) {
                    showShareStatus('Gagal membagikan link');
                }
            });
        });
    </script>
</x-layouts.portal>
