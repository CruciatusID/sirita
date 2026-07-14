<x-filament-panels::page>
    {{-- Header Laporan Cetak (Hanya Muncul saat Print) --}}
    <div class="print-header hidden" style="border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 20px; text-align: center;">
        <h1 style="font-size: 24px; font-weight: bold; text-transform: uppercase; margin: 0; color: #1e293b;">Laporan Insight & Analitik</h1>
        <p style="font-size: 16px; margin: 4px 0 0 0; color: #475569; font-weight: 500;">SIRITA — Portal Berita Kemenag Tana Toraja</p>
        <p style="font-size: 14px; margin: 8px 0 0 0; font-style: italic; color: #64748b;">
            Periode: {{ \Carbon\Carbon::createFromDate((int) $year, (int) $month, 1)->translatedFormat('F Y') }}
        </p>
    </div>

    {{-- Dropdown Filter Bulan & Tahun Menggunakan Komponen Native Filament --}}
    <div class="filter-container" style="display: flex; flex-direction: row; gap: 16px; align-items: center; margin-bottom: 24px;">
        <div style="flex: 1; max-width: 240px; min-width: 120px;">
            <span style="display: block; font-size: 12px; font-weight: 600; color: #9ca3af; margin-bottom: 6px;">Pilih Bulan</span>
            <x-filament::input.wrapper>
                <x-filament::input.select wire:model.live="month" id="filter-month">
                    <option value="01">Januari</option>
                    <option value="02">Februari</option>
                    <option value="03">Maret</option>
                    <option value="04">April</option>
                    <option value="05">Mei</option>
                    <option value="06">Juni</option>
                    <option value="07">Juli</option>
                    <option value="08">Agustus</option>
                    <option value="09">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </x-filament::input.select>
            </x-filament::input.wrapper>
        </div>

        <div style="flex: 1; max-width: 240px; min-width: 120px;">
            <span style="display: block; font-size: 12px; font-weight: 600; color: #9ca3af; margin-bottom: 6px;">Pilih Tahun</span>
            <x-filament::input.wrapper>
                <x-filament::input.select wire:model.live="year" id="filter-year">
                    @for ($y = now()->year; $y >= now()->year - 3; $y--)
                        <option value="{{ sprintf('%04d', $y) }}">{{ $y }}</option>
                    @endfor
                </x-filament::input.select>
            </x-filament::input.wrapper>
        </div>
    </div>

    {{-- Widgets Analitik --}}
    <div class="mb-6">
        @livewire(\App\Filament\Widgets\InsightStatsOverview::class, ['month' => $month, 'year' => $year], key('stats-' . $month . '-' . $year))
    </div>

    <div style="display: flex; flex-direction: row; gap: 24px; margin-bottom: 24px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 300px;">
            @livewire(\App\Filament\Widgets\MonthlyPostsChart::class, ['month' => $month, 'year' => $year], key('posts-chart-' . $month . '-' . $year))
        </div>
        <div style="flex: 1; min-width: 300px;">
            @livewire(\App\Filament\Widgets\CategoryViewsChart::class, ['month' => $month, 'year' => $year], key('category-chart-' . $month . '-' . $year))
        </div>
    </div>

    {{-- Tabel Berita Terpopuler & Kontributor Teraktif Menggunakan Section Native Filament --}}
    <div style="display: flex; flex-direction: row; gap: 24px; margin-top: 16px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 300px;">
            <!-- Tabel Berita Terpopuler -->
            <x-filament::section icon="heroicon-o-fire" icon-color="warning" heading="5 Berita Terpopuler">
                <div style="overflow-x: auto; width: 100%;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                        <thead>
                            <tr style="color: #9ca3af; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">
                                <th style="padding: 12px 16px 12px 0; text-align: left; border-bottom: 2px solid rgba(156, 163, 175, 0.3);">Judul</th>
                                <th style="padding: 12px 16px; text-align: left; border-bottom: 2px solid rgba(156, 163, 175, 0.3);">Kategori</th>
                                <th style="padding: 12px 0 12px 16px; text-align: right; border-bottom: 2px solid rgba(156, 163, 175, 0.3);">Jumlah Dibaca</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($popularPosts as $post)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                    <td style="padding: 16px 16px 16px 0; text-align: left; max-width: 250px; border-bottom: 1px solid rgba(156, 163, 175, 0.15);">
                                        <div style="font-weight: 500; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: inherit;" title="{{ $post->title }}">
                                            {{ $post->title }}
                                        </div>
                                        <div style="font-size: 12px; color: #9ca3af; margin-top: 4px;">
                                            Oleh: <span style="font-weight: 500; color: inherit;">{{ $post->author->name ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td style="padding: 16px; text-align: left; color: #9ca3af; white-space: nowrap; border-bottom: 1px solid rgba(156, 163, 175, 0.15);">
                                        {{ $post->category->name ?? '-' }}
                                    </td>
                                    <td style="padding: 16px 0 16px 16px; text-align: right; font-weight: 600; color: #f59e0b; border-bottom: 1px solid rgba(156, 163, 175, 0.15);">
                                        {{ number_format($post->monthly_views) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="padding: 24px 0; text-align: center; color: #9ca3af; border-bottom: 1px solid rgba(156, 163, 175, 0.15);">Belum ada log dibaca pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-filament::section>
        </div>

        <div style="flex: 1; min-width: 300px;">
            <!-- Tabel Kontributor Paling Aktif -->
            <x-filament::section icon="heroicon-o-users" icon-color="success" heading="5 Kontributor Teraktif">
                <div style="overflow-x: auto; width: 100%;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                        <thead>
                            <tr style="color: #9ca3af; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">
                                <th style="padding: 12px 16px 12px 0; text-align: left; border-bottom: 2px solid rgba(156, 163, 175, 0.3);">Nama Kontributor</th>
                                <th style="padding: 12px 16px; text-align: right; border-bottom: 2px solid rgba(156, 163, 175, 0.3);">Berita Terbit</th>
                                <th style="padding: 12px 0 12px 16px; text-align: right; border-bottom: 2px solid rgba(156, 163, 175, 0.3);">Total Dibaca</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($topContributors as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                    <td style="padding: 16px 16px 16px 0; text-align: left; border-bottom: 1px solid rgba(156, 163, 175, 0.15);">
                                        <div style="font-weight: 500; color: inherit;">{{ $user->name }}</div>
                                        <div style="font-size: 12px; color: #9ca3af; margin-top: 4px;">{{ $user->email }}</div>
                                    </td>
                                    <td style="padding: 16px; text-align: right; color: #9ca3af; white-space: nowrap; border-bottom: 1px solid rgba(156, 163, 175, 0.15);">
                                        {{ number_format($user->published_posts_count) }} berita
                                    </td>
                                    <td style="padding: 16px 0 16px 16px; text-align: right; font-weight: 600; color: #10b981; border-bottom: 1px solid rgba(156, 163, 175, 0.15);">
                                        {{ number_format($user->total_views) }} pembaca
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="padding: 24px 0; text-align: center; color: #9ca3af; border-bottom: 1px solid rgba(156, 163, 175, 0.15);">Belum ada aktivitas kontributor pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-filament::section>
        </div>
    </div>

    <div style="display: flex; flex-direction: row; gap: 24px; margin-top: 24px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 300px;">
            <!-- Tabel Sumber Lalu Lintas -->
            <x-filament::section icon="heroicon-o-globe-alt" icon-color="info" heading="Sumber Lalu Lintas (Referrers)">
                <div style="overflow-x: auto; width: 100%;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                        <thead>
                            <tr style="color: #9ca3af; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">
                                <th style="padding: 12px 16px 12px 0; text-align: left; border-bottom: 2px solid rgba(156, 163, 175, 0.3);">Sumber</th>
                                <th style="padding: 12px 0 12px 16px; text-align: right; border-bottom: 2px solid rgba(156, 163, 175, 0.3);">Views</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($referrers as $ref)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                    <td style="padding: 16px 16px 16px 0; text-align: left; border-bottom: 1px solid rgba(156, 163, 175, 0.15); font-weight: 500;">
                                        {{ $ref->referrer }}
                                    </td>
                                    <td style="padding: 16px 0 16px 16px; text-align: right; font-weight: 600; color: #3b82f6; border-bottom: 1px solid rgba(156, 163, 175, 0.15);">
                                        {{ number_format($ref->views_count) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" style="padding: 24px 0; text-align: center; color: #9ca3af; border-bottom: 1px solid rgba(156, 163, 175, 0.15);">Belum ada data rujukan lalu lintas pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-filament::section>
        </div>

        <div style="flex: 1; min-width: 300px;">
            <!-- Tabel Browser Pengunjung -->
            <x-filament::section icon="heroicon-o-computer-desktop" icon-color="primary" heading="Browser Pengunjung">
                <div style="overflow-x: auto; width: 100%;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                        <thead>
                            <tr style="color: #9ca3af; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">
                                <th style="padding: 12px 16px 12px 0; text-align: left; border-bottom: 2px solid rgba(156, 163, 175, 0.3);">Nama Browser</th>
                                <th style="padding: 12px 0 12px 16px; text-align: right; border-bottom: 2px solid rgba(156, 163, 175, 0.3);">Penggunaan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($browsers as $browser)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                    <td style="padding: 16px 16px 16px 0; text-align: left; border-bottom: 1px solid rgba(156, 163, 175, 0.15); font-weight: 500;">
                                        {{ $browser->name }}
                                    </td>
                                    <td style="padding: 16px 0 16px 16px; text-align: right; font-weight: 600; color: #6366f1; border-bottom: 1px solid rgba(156, 163, 175, 0.15);">
                                        {{ number_format($browser->count) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" style="padding: 24px 0; text-align: center; color: #9ca3af; border-bottom: 1px solid rgba(156, 163, 175, 0.15);">Belum ada data browser pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-filament::section>
        </div>
    </div>

    {{-- CSS Khusus untuk Mengatur Tampilan Cetak / Save to PDF --}}
    <style>
        @media screen {
            .print-header {
                display: none !important;
            }
        }
        @media print {
            /* Reset Layout Utama agar tidak terjadi infinite looping halaman kosong */
            html, 
            body, 
            .fi-layout, 
            .fi-main, 
            .fi-main-ctn, 
            .fi-body,
            main {
                height: auto !important;
                min-height: auto !important;
                overflow: visible !important;
                position: static !important;
                display: block !important;
            }

            /* Tampilkan header laporan resmi */
            .print-header {
                display: block !important;
            }
            
            /* Sembunyikan elemen navigasi Filament, breadcrumbs, header bawaan, dan form filter */
            .fi-sidebar,
            .fi-topbar,
            .fi-breadcrumbs,
            .fi-header,
            .filter-container {
                display: none !important;
            }
            
            /* Bersihkan background warna admin panel */
            body, 
            .fi-body, 
            .fi-layout {
                background-color: #ffffff !important;
                color: #000000 !important;
            }
            
            /* Hilangkan padding default pada kertas */
            .fi-main {
                padding: 0 !important;
                margin: 0 !important;
            }
            
            .fi-main-ctn {
                max-width: 100% !important;
                width: 100% !important;
            }
            
            /* Ubah tata letak dua kolom (grid) menjadi vertikal rapi */
            .grid {
                display: block !important;
            }
            
            .grid > * {
                margin-bottom: 24px !important;
                page-break-inside: avoid !important;
                border: 1px solid #e2e8f0 !important;
                box-shadow: none !important;
                background-color: #ffffff !important;
            }
            
            /* Maksimalkan keterbacaan tabel di atas kertas */
            table {
                font-size: 12px !important;
                width: 100% !important;
            }
            
            th, td {
                padding: 8px 12px !important;
                border-bottom: 1px solid #e2e8f0 !important;
            }
            
            /* Paksa grafik Chart.js memiliki ukuran penuh */
            canvas {
                max-width: 100% !important;
                height: auto !important;
            }
        }
    </style>
</x-filament-panels::page>
