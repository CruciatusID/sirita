<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
</head>
<body class="min-h-screen bg-stone-50 text-stone-900 antialiased">
    <header class="sticky top-0 z-50 border-b border-stone-200 bg-white/80 backdrop-blur-md">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-5 py-3 md:flex-row md:items-center md:justify-between">
            <a href="{{ route('home') }}" class="group flex items-center gap-3">
                <img src="{{ asset('images/logo-kemenag.png') }}" alt="Logo Kemenag" class="h-11 w-auto transition-transform group-hover:scale-105">
                <span>
                    <span class="block text-xl font-black leading-none tracking-tight text-emerald-950">SIRITA</span>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-stone-500">Sistem Informasi Religi Tana Toraja</span>
                </span>
            </a>
            <nav class="flex flex-wrap items-center gap-1 text-sm font-bold text-stone-600">
                <a class="rounded-full px-4 py-2 transition-colors hover:bg-emerald-50 hover:text-emerald-800 {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-800' : '' }}" href="{{ route('home') }}">Beranda</a>
                <a class="rounded-full px-4 py-2 transition-colors hover:bg-emerald-50 hover:text-emerald-800" href="{{ route('pages.show', 'profil-kantor') }}">Profil</a>
                <a class="rounded-full px-4 py-2 transition-colors hover:bg-emerald-50 hover:text-emerald-800" href="{{ route('pages.show', 'ppid') }}">PPID</a>
                <a class="ml-2 rounded-full bg-emerald-800 px-5 py-2 text-white shadow-lg shadow-emerald-900/20 transition-all hover:bg-emerald-900 hover:shadow-xl" href="/admin">Panel Admin</a>
            </nav>
        </div>
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
                <p class="mt-3">Kabupaten Tana Toraja, Sulawesi Selatan</p>
            </div>
            <div class="text-sm text-stone-400">
                <p class="font-bold uppercase tracking-[0.22em] text-amber-200">Sistem</p>
                <p class="mt-3">Laravel, Filament, dan MySQL lokal untuk tahap development.</p>
            </div>
        </div>
    </footer>
</body>
</html>
