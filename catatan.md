# Catatan SIRITA

## Status Saat Ini

SIRITA sudah dibuat sebagai proyek Laravel 13 dengan admin panel Filament dan database MySQL lokal via Herd/phpMyAdmin.

Stack awal:

- Laravel 13.7
- PHP 8.3+ (lokal saat ini PHP 8.4)
- MySQL lokal untuk development
- Filament 5.6 untuk admin panel
- Blade + Tailwind CSS untuk frontend
- Spatie Permission untuk role
- Spatie Activity Log, Sitemap, Backup, Sluggable, Image Optimizer
- Artesaos SEO Tools

## Database Development

Database development menggunakan MySQL:

```text
Database: kemenagt_sirita
```

Konfigurasi ada di:

```text
.env
.env.example
```

Nilai penting:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kemenagt_sirita
DB_USERNAME=root
DB_PASSWORD=
FILESYSTEM_DISK=public
```

Jika database MySQL belum ada, buat database baru lewat phpMyAdmin:

```text
Nama database: kemenagt_sirita
Collation: utf8mb4_unicode_ci
```

Setelah database dibuat dan kredensial `.env` sudah benar, jalankan:

```powershell
php artisan migrate --seed
```

## Login Admin

URL admin:

```text
/admin
```

Akun awal:

```text
Email: admin@sirita.local
Password: password
```

Role awal:

- Super Admin
- Admin Humas
- Editor
- Kontributor

## Fitur Yang Sudah Ada

Admin Filament:

- Berita
- Kategori
- Tag
- Unit kerja
- Halaman statis
- Banner
- Media
- User

Frontend publik:

- `/`
- `/berita/{slug}`
- `/kategori/{slug}`
- `/unit/{slug}`
- `/halaman/{slug}`
- `/sitemap.xml`

Seeder awal:

- Role
- Unit kerja Kantor Kemenag Tana Toraja, seksi/penyelenggara, 19 KUA, dan 7 Madrasah
- Kategori berita Kemenag Tana Toraja, seksi/penyelenggara, KUA beserta 19 subkategori, dan Madrasah beserta 7 subkategori
- Tag awal untuk topik umum layanan, keagamaan, unit, pengumuman, dan kegiatan
- Halaman Profil, Visi Misi, Struktur Organisasi, Kontak, PPID
- Akun Super Admin

## Cara Menjalankan Setelah Dipindah Ke Folder Herd

Masuk ke folder proyek baru, lalu jalankan:

```powershell
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run build
```

Jika file `.env` ikut dipindahkan dan database MySQL sudah ada, biasanya tidak perlu `copy .env.example .env`, `key:generate`, dan `migrate --seed` lagi.

Untuk menjalankan server manual:

```powershell
php artisan serve
```

Untuk development asset:

```powershell
npm run dev
```

Jika memakai Herd, cukup pastikan folder proyek dikenali Herd dan document root mengarah ke folder `public`.

## Verifikasi Terakhir

Perintah yang sudah berhasil:

```powershell
php artisan migrate:fresh --seed
npm install
npm run build
php artisan test
```

Hasil test terakhir:

```text
2 passed
```

Langkah yang sudah dirapikan:

1. Buat slug otomatis dari judul/nama di form admin.
2. Rapikan label menu admin ke bahasa Indonesia.
3. Sesuaikan kategori berita dan tambahkan subkategori KUA/Madrasah.
4. Sesuaikan unit kerja dengan struktur Kemenag Tana Toraja, KUA, dan Madrasah.
5. Tambahkan tag awal dan fitur membuat tag langsung dari form Berita.
6. Gambar utama dan gambar share berita bisa dipilih dari Media atau upload gambar baru dari form Berita.
7. Lampiran gambar di kolom Isi Berita tersimpan ke storage publik dan tercatat di menu Media.
8. Gambar yang disisipkan di kolom Isi Berita bisa dipilih dari Media/upload baru dan diberi teks alternatif. Jika ingin mengganti gambar di badan teks, hapus dulu gambar lama lalu sisipkan gambar baru.

Langkah berikutnya yang perlu dirapikan:

1. Tambahkan sample berita agar halaman depan tidak kosong.
2. Rapikan role permission per menu.
3. Tambahkan approval workflow untuk Editor dan Kontributor.
4. Tambahkan dashboard statistik berita.
5. Rapikan desain frontend sesuai identitas Kemenag.

## Progress 2026-05-05

Perubahan portal publik:

1. Homepage sudah dirapikan dengan susunan:
   - marquee Kategori Populer tepat di bawah header,
   - headline/berita utama di kiri,
   - Terpopuler 7 Hari di kanan,
   - banner aktif,
   - grid Berita Terbaru di bawah.
2. Kategori Populer dihitung dari jumlah berita berstatus published per kategori.
3. Terpopuler 7 Hari dihitung dari berita published dalam 7 hari terakhir, diurutkan dari jumlah views terbanyak.
4. Search publik ditambahkan:
   - route: `/cari?q=kata-kunci`
   - mencari pada judul, ringkasan, isi, dan nama kategori.
5. Header publik sudah punya navigasi mobile hamburger.
6. Search bar ditempatkan di kanan bar kategori/marquee pada desktop, dan turun rapi pada mobile.
7. Detail berita menampilkan meta dengan format:
   - `Tayang: ... WITA`
   - `Penulis: ...`
8. Statistik berita ditampilkan di detail berita:
   - views,
   - suka,
   - dibagikan.
9. Tombol suka dan bagikan dipindahkan ke bawah artikel, sisi kanan, memakai ikon.
10. Tombol bagikan sekarang:
    - membuka native share dialog jika browser mendukung,
    - menyalin link jika native share tidak tersedia,
    - menampilkan pesan status seperti `Link berita disalin`,
    - menaikkan counter share setelah share/copy berhasil.

Perubahan admin dan media:

1. Batas upload gambar diset 300 KB di form banner, media, pilihan media, dan rich editor.
2. Media punya field caption.
3. Post punya field caption gambar utama.
4. Caption gambar utama bisa otomatis terisi dari caption Media, tetapi tetap bisa diedit.
5. Tanggal Terbit otomatis terisi waktu saat ini saat membuat berita baru, tetapi tetap bisa diubah.
6. Setelah membuat berita, notifikasi menjadi `Berita berhasil ditambahkan`.
7. Setelah membuat berita, admin diarahkan kembali ke list Berita.
8. Image optimizer hanya berjalan saat path file Media berubah, bukan setiap metadata media disimpan.

Pembatasan menu Filament berdasarkan role:

1. Super Admin: semua menu.
2. Admin Humas: Berita, Media, Kategori, Tag, Unit Kerja, Halaman, Banner.
3. Editor: Berita, Media, Kategori, Tag.
4. Kontributor: Berita dan Media.

File penting yang ditambahkan/diubah:

- `app/Filament/Support/AdminAccess.php`
- `database/migrations/2026_05_05_120000_add_feedback_counts_to_posts_table.php`
- `resources/views/portal/search.blade.php`
- `resources/views/portal/home.blade.php`
- `resources/views/portal/post.blade.php`
- `resources/views/components/layouts/portal.blade.php`
- `resources/js/app.js`
- `resources/css/app.css`

Perintah penting saat lanjut di komputer lain:

```powershell
git pull
composer install
npm install
php artisan migrate
php artisan optimize:clear
npm run dev
```

Catatan:

- Jika `npm run dev` sudah berjalan, tidak perlu `npm run build` selama development.
- Migration terbaru wajib dijalankan karena menambahkan `likes_count` dan `shares_count` di tabel `posts`.
- Field Editor berita belum ditambahkan. Saat ini detail berita hanya menampilkan Tayang dan Penulis. Jika ingin format `Penulis: ... | Editor: ...`, perlu tambah field/relasi editor di tabel posts dan form admin.
