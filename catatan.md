# Catatan SIRITA

## Status Saat Ini

SIRITA adalah proyek Laravel 12 dengan admin panel Filament dan database MySQL lokal via Herd/phpMyAdmin.

Stack saat ini:

- Laravel 12
- PHP 8.2+
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
Username: admin
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

Langkah berikutnya yang masih perlu dirapikan:

1. Rapikan desain frontend sesuai identitas Kemenag.
2. Evaluasi ulang package `spatie/laravel-backup` jika hosting tidak menyediakan extension `zip`.
3. Uji ulang penuh fitur admin dan portal di hosting production setelah upload zip terbaru.

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

Dummy data berita:

1. Ditambahkan 3 kontributor dari seksi berbeda:
   - Bimas Islam
   - Bimas Kristen
   - Pendidikan Islam
2. Ditambahkan 6 dummy berita dengan kategori dan tag yang berbeda-beda.
3. Setiap berita memakai gambar placeholder yang bisa diganti nanti:
   - `media/dummy-news-01.jpg`
   - `media/dummy-news-02.jpg`
   - `media/dummy-news-03.jpg`
   - `media/dummy-news-04.jpg`
   - `media/dummy-news-05.jpg`
   - `media/dummy-news-06.jpg`
   - `media/dummy-news-07.jpg`
   - `media/dummy-news-08.jpg`
   - `media/dummy-news-09.jpg`
   - file sumber gambar ada di folder `contohgambar`
   - saat seed ulang di komputer lain, `SampleNewsSeeder` menyalin gambar itu ke `storage/app/public/media` dengan nama yang sama
   - `SampleNewsSeeder` juga menyinkronkan file di folder `media` ke tabel `media`, supaya tampil di menu Media
   - pemetaan tema saat ini: 01 rapat koordinasi, 02 pembinaan ASN, 03 dialog lintas iman, 04 literasi digital madrasah, 05 layanan nikah, 06 pembinaan siswa, 07 remaja dan game, 08 keluarga dan game, 09 panduan bijak game
4. Data editor berita diisi otomatis lewat relasi `editor_user_id`, supaya detail berita bisa menampilkan `Penulis` dan `Editor` tanpa field manual di form.
5. List berita admin sekarang punya tab cepat `Semua / Draft / Review / Terbit / Ditolak`.
6. Ditambahkan 3 dummy berita baru bertema game online dari sisi agama, dengan penulis langsung akun editor.

Pembatasan menu Filament berdasarkan role:

1. Super Admin: semua menu.
2. Admin Humas: Berita, Media, Kategori, Tag, Unit Kerja, Halaman, Banner.
3. Editor: Berita, Media, Kategori, Tag.
4. Kontributor: Berita dan Media.

Progress terbaru:

1. Penyimpanan gambar disederhanakan ke satu folder publik `storage/app/public/media`:
   - gambar utama berita,
   - gambar share/OG,
   - gambar isi berita rich editor,
   - gambar banner.
2. Share detail berita diganti menjadi tombol WhatsApp dan Facebook:
   - WhatsApp memakai format `Judul Berita`, baris kosong, `Baca selengkapnya:`, lalu link berita,
   - Facebook memakai `https://www.facebook.com/sharer/sharer.php?u=...`,
   - keduanya tetap menaikkan counter `shares_count`.
3. Header portal menampilkan tanggal hari ini di desktop dan teks logo mobile dirapikan.
4. Footer portal diperbarui:
   - alamat kantor: `Jl. Pongtiku No. 106, Makale, Tana Toraja`,
   - copyright,
   - keterangan `Dikelola oleh HDI Kemenag Tana Toraja`,
   - tautan resmi bersusun dengan ikon: website utama, halaman Kemenag Sulsel, Instagram, dan Facebook.
5. Dashboard Super Admin Filament mulai dikembangkan:
   - `FilamentInfoWidget` bawaan dihapus,
   - ditambahkan widget ringkasan portal,
   - ditambahkan tabel berita menunggu review,
   - ditambahkan tabel berita terpopuler.
6. Ikon navigasi resource Filament diganti agar lebih mudah dipindai:
   - Berita, Kategori, Tag, Unit Kerja, Halaman, Banner, Media, dan Pengguna memakai Heroicons yang lebih spesifik.
7. Footer sidebar admin ditambahkan di kiri bawah:
   - copyright SIRITA,
   - `Dikelola oleh HDI Kemenag Tana Toraja`.

File penting yang ditambahkan/diubah:

- `app/Filament/Support/AdminAccess.php`
- `app/Filament/Resources/Posts/Pages/EditPost.php`
- `app/Http/Controllers/PortalController.php`
- `app/Models/Post.php`
- `app/Filament/Resources/Posts/PostResource.php`
- `app/Filament/Resources/Posts/Tables/PostsTable.php`
- `database/migrations/2026_05_05_120000_add_feedback_counts_to_posts_table.php`
- `database/migrations/2026_05_05_173000_add_editor_name_to_posts_table.php`
- `database/migrations/2026_05_05_181000_add_editor_user_id_to_posts_table.php`
- `database/seeders/SampleNewsSeeder.php`
- `resources/views/portal/search.blade.php`
- `resources/views/portal/home.blade.php`
- `resources/views/portal/post.blade.php`
- `resources/views/components/layouts/portal.blade.php`
- `resources/js/app.js`
- `resources/css/app.css`

## Progress 2026-05-13

Penyesuaian production dan deploy:

1. Stack project diturunkan ke PHP 8.2 dan Laravel 12 agar cocok dengan hosting.
2. Login admin diubah dari email menjadi username.
3. Email user dibuat opsional untuk kebutuhan SMTP di masa depan.
4. SQL awal untuk phpMyAdmin dibuat di `database/sql/kemenagt_berita_import.sql`.
5. Zip deploy lokal dibuat di `_deploy/sirita-hosting-upload.zip` untuk upload manual hosting.
6. Hosting diarahkan ke `public_html/sirita/public`.

Perbaikan production:

1. Login admin production berhasil setelah hash bcrypt admin diperbaiki di database hosting.
2. Extension hosting yang perlu aktif: `intl`, `zip`, `fileinfo`, `mbstring`, `openssl`, `xml`, `ctype`, dan `tokenizer`.
3. Struktur `activity_log` hosting perlu kolom `batch_uuid`.
4. Model Eloquent diubah dari PHP attribute `#[Fillable]`/`#[Hidden]` ke properti standar `$fillable`/`$hidden`.
5. Method activity log di `Post` disesuaikan dari `dontLogEmptyChanges()` ke `dontSubmitEmptyLogs()`.
6. Provider panel admin memakai `ValidateCsrfToken` untuk kompatibilitas Laravel 12.

Alur admin terbaru:

1. Kontributor hanya bisa membuat berita `Draft` atau `Kirim untuk Review`.
2. Kontributor hanya melihat berita miliknya sendiri.
3. Editor melihat berita status `review`, `published`, atau `rejected`, tetapi tidak melihat draft kontributor.
4. Super Admin dan Admin Humas tetap melihat semua berita.
5. Dashboard menampilkan ringkasan sesuai role.
6. Halaman `Profil Saya` ditambahkan untuk semua user agar bisa ubah nama tampilan, email, dan password sendiri.
7. Form pengguna menambahkan `Konfirmasi Password`.
8. Registrasi kontributor tersedia melalui `/admin/daftar-kontributor`; tombol daftar tidak ditampilkan di halaman login.
9. Bug Firefox di dashboard setelah navigasi balik dari menu lain ditangani dengan reset scroll khusus Firefox pada URL `/admin`.

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
- Migration wajib dijalankan setelah pull karena ada perubahan struktur tabel `users`, `posts`, dan `activity_log`.
- Detail berita sudah memiliki relasi editor melalui `editor_user_id`.
- Catatan deploy dan patch SQL hosting ada di `PROGRESS_DEPLOY.md`.
