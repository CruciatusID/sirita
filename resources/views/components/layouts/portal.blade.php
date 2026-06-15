<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $metaTitle = $title ?? 'SIRITA - Kemenag Tana Toraja';
        $metaDescription = $description ?? 'Portal berita resmi Kementerian Agama Kabupaten Tana Toraja.';
        $metaImage = filled($image ?? null) ? asset('storage/' . $image) : null;
    @endphp
    <title>{{ $metaTitle }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-kemenag.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-serif-news { font-family: 'Playfair Display', serif; }
    </style>
    <meta name="description" content="{{ $metaDescription }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if ($metaImage)
        <meta property="og:image" content="{{ $metaImage }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:image" content="{{ $metaImage }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-stone-50 text-stone-900 antialiased">
    <header class="sticky top-0 z-50 border-b border-stone-200 bg-white/90 backdrop-blur-md">
        @php
            $headerDate = ucfirst(now()->locale('id')->translatedFormat('l, j F Y'));
        @endphp

        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-5 py-3">
            <a href="{{ route('home') }}" class="group flex items-center gap-3">
                <img src="{{ asset('images/logo-kemenag.png') }}" alt="Logo Kemenag" class="h-11 w-auto transition-transform group-hover:scale-105">
                <span class="min-w-0">
                    <span class="block text-xl font-black leading-none tracking-tight text-emerald-950">SIRITA</span>
                    <span class="mt-1 block max-w-[11rem] text-[10px] font-bold uppercase leading-tight tracking-wider text-stone-500 sm:max-w-none">Sistem Informasi Religi Tana Toraja</span>
                </span>
            </a>
            <button type="button" class="grid h-10 w-10 place-items-center rounded-full border border-stone-200 text-stone-700 md:hidden" data-mobile-menu-button aria-controls="portal-mobile-menu" aria-expanded="false" aria-label="Buka menu navigasi">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <path d="M4 7h16" />
                    <path d="M4 12h16" />
                    <path d="M4 17h16" />
                </svg>
            </button>
            <div class="hidden min-w-max text-center text-sm font-bold text-stone-500 lg:block">
                {{ $headerDate }}
            </div>
            <nav class="hidden items-center gap-1 text-sm font-bold text-stone-600 md:flex">
                <a class="rounded-full px-4 py-2 transition-colors hover:bg-emerald-50 hover:text-emerald-800 {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-800' : '' }}" href="{{ route('home') }}">Beranda</a>
                <a class="rounded-full px-4 py-2 transition-colors hover:bg-emerald-50 hover:text-emerald-800" href="{{ route('pages.show', 'profil-kantor') }}">Profil</a>
                <a class="rounded-full px-4 py-2 transition-colors hover:bg-emerald-50 hover:text-emerald-800" href="{{ route('pages.show', 'ppid') }}">PPID</a>
                <a class="ml-2 rounded-full bg-emerald-800 px-5 py-2 text-white shadow-lg shadow-emerald-900/20 transition-all hover:bg-emerald-900 hover:shadow-xl" href="/admin">Panel Admin</a>
            </nav>
        </div>
        <nav id="portal-mobile-menu" class="hidden border-t border-stone-200 bg-white px-5 py-3 text-sm font-bold text-stone-700 md:hidden" data-mobile-menu>
            <div class="mx-auto grid max-w-7xl gap-2">
                <a class="rounded-xl px-4 py-3 transition-colors hover:bg-emerald-50 hover:text-emerald-800 {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-800' : '' }}" href="{{ route('home') }}">Beranda</a>
                <a class="rounded-xl px-4 py-3 transition-colors hover:bg-emerald-50 hover:text-emerald-800" href="{{ route('pages.show', 'profil-kantor') }}">Profil</a>
                <a class="rounded-xl px-4 py-3 transition-colors hover:bg-emerald-50 hover:text-emerald-800" href="{{ route('pages.show', 'ppid') }}">PPID</a>
                <a class="rounded-xl bg-emerald-800 px-4 py-3 text-white transition-colors hover:bg-emerald-900" href="/admin">Panel Admin</a>
            </div>
        </nav>
    </header>

    <main>
        {{ $slot }}
    </main>

    <footer class="mt-16 border-t border-stone-200 bg-stone-950 text-stone-200">
        <div class="mx-auto grid max-w-7xl gap-6 px-5 py-10 md:grid-cols-3">
            <div>
                <p class="font-serif text-3xl font-black text-white">SIRITA</p>
                <p class="mt-3 text-sm leading-6 text-stone-400">Portal berita dan publikasi digital resmi Kementerian Agama Kabupaten Tana Toraja.</p>
            </div>
            <div class="text-sm text-stone-400">
                <p class="font-bold uppercase tracking-[0.22em] text-amber-200">Alamat</p>
                <p class="mt-3">Jl. Pongtiku No. 106, Makale, Tana Toraja</p>
            </div>
            <div class="text-sm text-stone-400">
                <p class="font-bold uppercase tracking-[0.22em] text-amber-200">Tautan Resmi</p>
                <div class="mt-3 grid gap-2.5">
                    <a href="https://tanatoraja.kemenag.go.id/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 transition-colors hover:text-amber-200">
                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M2 12h20" />
                            <path d="M12 2a15.3 15.3 0 0 1 0 20" />
                            <path d="M12 2a15.3 15.3 0 0 0 0 20" />
                        </svg>
                        <span>tanatoraja.kemenag.go.id</span>
                    </a>
                    <a href="https://sulsel.kemenag.go.id/offices/tana-toraja" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 transition-colors hover:text-amber-200">
                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M3 21h18" />
                            <path d="M5 21V7l7-4 7 4v14" />
                            <path d="M9 21v-8h6v8" />
                        </svg>
                        <span>Kemenag Sulsel - Tana Toraja</span>
                    </a>
                    <a href="https://www.instagram.com/kemenag.tana_toraja/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 transition-colors hover:text-amber-200">
                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <rect x="3" y="3" width="18" height="18" rx="5" />
                            <circle cx="12" cy="12" r="4" />
                            <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none" />
                        </svg>
                        <span>@kemenag.tana_toraja</span>
                    </a>
                    <a href="https://www.facebook.com/kemenagtoraja/?locale=id_ID" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 transition-colors hover:text-amber-200">
                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M14 8.5h2.5V5.1c-.43-.06-1.9-.19-3.62-.19-3.58 0-6.03 2.25-6.03 6.38v3.8H3v3.8h3.85V24h4.72v-5.11h3.69l.59-3.8h-4.28v-3.42c0-1.1.3-1.85 1.89-1.85H16V8.5h-2Z" />
                        </svg>
                        <span>Kemenag Tana Toraja</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="border-t border-white/10">
            <div class="mx-auto flex max-w-7xl flex-col gap-2 px-5 py-4 text-xs text-stone-500 md:flex-row md:items-center md:justify-between">
                <p>&copy; {{ now()->year }} SIRITA Kemenag Tana Toraja. Hak cipta dilindungi.</p>
                <p>Dikelola oleh HDI Kemenag Tana Toraja.</p>
            </div>
        </div>
    </footer>
</body>
</html>
