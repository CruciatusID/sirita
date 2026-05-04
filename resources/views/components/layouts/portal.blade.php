<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'SIRITA - Kemenag Tana Toraja' }}</title>
    <meta name="description" content="{{ $description ?? 'Portal berita resmi Kementerian Agama Kabupaten Tana Toraja.' }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f7f2e8] text-stone-900 antialiased">
    <div class="pointer-events-none fixed inset-0 -z-10 bg-[radial-gradient(circle_at_top_left,rgba(16,185,129,.18),transparent_35%),radial-gradient(circle_at_80%_10%,rgba(245,158,11,.18),transparent_30%)]"></div>

    <header class="border-b border-stone-200/80 bg-[#f7f2e8]/90 backdrop-blur">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-5 py-5 md:flex-row md:items-center md:justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <span class="grid h-12 w-12 place-items-center rounded-2xl bg-emerald-800 font-serif text-xl font-black text-amber-100">S</span>
                <span>
                    <span class="block font-serif text-3xl font-black leading-none tracking-tight">SIRITA</span>
                    <span class="text-xs font-bold uppercase tracking-[0.22em] text-stone-600">Kemenag Tana Toraja</span>
                </span>
            </a>
            <nav class="flex flex-wrap gap-3 text-sm font-bold text-stone-700">
                <a class="rounded-full bg-white px-4 py-2 shadow-sm hover:bg-emerald-800 hover:text-white" href="{{ route('home') }}">Beranda</a>
                <a class="rounded-full bg-white px-4 py-2 shadow-sm hover:bg-emerald-800 hover:text-white" href="{{ route('pages.show', 'profil-kantor') }}">Profil</a>
                <a class="rounded-full bg-white px-4 py-2 shadow-sm hover:bg-emerald-800 hover:text-white" href="{{ route('pages.show', 'ppid') }}">PPID</a>
                <a class="rounded-full bg-stone-950 px-4 py-2 text-white shadow-sm hover:bg-emerald-800" href="/admin">Admin</a>
            </nav>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    <footer class="mt-20 border-t border-stone-200 bg-stone-950 text-stone-200">
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
                <p class="mt-3">Laravel, Filament, dan SQLite lokal untuk tahap development.</p>
            </div>
        </div>
    </footer>
</body>
</html>
