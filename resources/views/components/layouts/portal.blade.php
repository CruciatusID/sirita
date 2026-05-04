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
    <header class="border-b border-stone-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-5 py-4 md:flex-row md:items-center md:justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <span class="grid h-11 w-11 place-items-center rounded bg-emerald-800 font-serif text-xl font-black text-white">S</span>
                <span>
                    <span class="block text-2xl font-black leading-none tracking-tight">SIRITA</span>
                    <span class="text-xs font-bold uppercase text-stone-500">Kemenag Tana Toraja</span>
                </span>
            </a>
            <nav class="flex flex-wrap items-center gap-1 text-sm font-semibold text-stone-700">
                <a class="px-3 py-2 hover:bg-stone-100 hover:text-emerald-800" href="{{ route('home') }}">Beranda</a>
                <a class="px-3 py-2 hover:bg-stone-100 hover:text-emerald-800" href="{{ route('pages.show', 'profil-kantor') }}">Profil</a>
                <a class="px-3 py-2 hover:bg-stone-100 hover:text-emerald-800" href="{{ route('pages.show', 'ppid') }}">PPID</a>
                <a class="bg-emerald-800 px-3 py-2 text-white hover:bg-emerald-900" href="/admin">Admin</a>
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
