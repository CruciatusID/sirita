<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Insight & Analitik - {{ $dateObj->translatedFormat('F Y') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            font-size: 13px;
            line-height: 1.5;
            color: #1e293b;
            padding: 30px 40px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #d97706;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }
        .header h1 {
            font-size: 22px;
            font-weight: 700;
            text-transform: uppercase;
            color: #1e293b;
            margin-bottom: 4px;
        }
        .header .sub {
            font-size: 14px;
            color: #475569;
        }
        .header .periode {
            font-size: 13px;
            color: #64748b;
            font-style: italic;
            margin-top: 6px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 28px;
        }
        .stat-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 14px 16px;
            text-align: center;
        }
        .stat-card .value {
            font-size: 22px;
            font-weight: 700;
            color: #d97706;
        }
        .stat-card .label {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }
        .section {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }
        .section-title {
            background: #f8fafc;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 600;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th {
            text-align: left;
            padding: 8px 12px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
            border-bottom: 2px solid #e2e8f0;
            font-weight: 600;
        }
        td {
            padding: 8px 12px;
            border-bottom: 1px solid #f1f5f9;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .text-right { text-align: right; }
        .font-medium { font-weight: 500; }
        .text-amber { color: #d97706; }
        .text-green { color: #10b981; }
        .text-blue { color: #3b82f6; }
        .text-indigo { color: #6366f1; }
        .text-muted { color: #94a3b8; }
        .text-sm { font-size: 11px; }
        .no-data {
            padding: 24px;
            text-align: center;
            color: #94a3b8;
        }

        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
            .section { break-inside: avoid; }
            .grid-2 { break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Insight & Analitik</h1>
        <div class="sub">SIRITA — Portal Berita Kemenag Tana Toraja</div>
        <div class="periode">Periode: {{ $dateObj->translatedFormat('F Y') }}</div>
    </div>

    <div class="stats">
        <div class="stat-card">
            <div class="value">{{ number_format($monthlyViews) }}</div>
            <div class="label">Pembaca ({{ $dateObj->translatedFormat('F Y') }})</div>
        </div>
        <div class="stat-card">
            <div class="value">{{ number_format($monthlyPosts) }}</div>
            <div class="label">Berita Terbit ({{ $dateObj->translatedFormat('F Y') }})</div>
        </div>
        <div class="stat-card">
            <div class="value">{{ number_format($avgViews, 1) }}</div>
            <div class="label">Rata-rata Dibaca</div>
        </div>
        <div class="stat-card">
            <div class="value">{{ number_format($allTimeViews) }}</div>
            <div class="label">Total Pembaca (Semua Waktu)</div>
        </div>
    </div>

    <div class="grid-2">
        <div class="section">
            <div class="section-title">5 Berita Terpopuler</div>
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th class="text-right">Dibaca</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($popularPosts as $post)
                        <tr>
                            <td>
                                <div class="font-medium">{{ $post->title }}</div>
                                <div class="text-muted text-sm">Oleh: {{ $post->author->name ?? '-' }}</div>
                            </td>
                            <td class="text-muted">{{ $post->category->name ?? '-' }}</td>
                            <td class="text-right text-amber font-medium">{{ number_format($post->monthly_views) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="no-data">Belum ada data pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="section">
            <div class="section-title">5 Kontributor Teraktif</div>
            <table>
                <thead>
                    <tr>
                        <th>Nama Kontributor</th>
                        <th class="text-right">Berita Terbit</th>
                        <th class="text-right">Total Dibaca</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($topContributors as $user)
                        <tr>
                            <td class="font-medium">{{ $user->name }}</td>
                            <td class="text-right text-muted">{{ number_format($user->published_posts_count) }} berita</td>
                            <td class="text-right text-green font-medium">{{ number_format($user->total_views) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="no-data">Belum ada data pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid-2">
        <div class="section">
            <div class="section-title">Sumber Lalu Lintas (Referrers)</div>
            <table>
                <thead>
                    <tr>
                        <th>Sumber</th>
                        <th class="text-right">Views</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($referrers as $ref)
                        <tr>
                            <td class="font-medium">{{ $ref->referrer }}</td>
                            <td class="text-right text-blue font-medium">{{ number_format($ref->views_count) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="no-data">Belum ada data pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Browser Pengunjung</div>
            <table>
                <thead>
                    <tr>
                        <th>Nama Browser</th>
                        <th class="text-right">Penggunaan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($browsers as $browser)
                        <tr>
                            <td class="font-medium">{{ $browser->name }}</td>
                            <td class="text-right text-indigo font-medium">{{ number_format($browser->count) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="no-data">Belum ada data pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 24px; padding: 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; color: #94a3b8; font-size: 12px;">
        <p>Dialog cetak akan muncul secara otomatis. Tutup tab ini setelah selesai.</p>
        <button onclick="window.print()" style="margin-top: 8px; padding: 8px 24px; background: #d97706; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">Cetak Sekarang</button>
    </div>

    <script>
        window.onload = function () {
            setTimeout(function () { window.print(); }, 300);
        };
        window.onafterprint = function () {
            window.close();
        };
    </script>
</body>
</html>
