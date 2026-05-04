# Catatan SIRITA

## Status Saat Ini

SIRITA sudah dibuat sebagai proyek Laravel 13 dengan admin panel Filament dan database offline SQLite.

Stack awal:

- Laravel 13.7
- PHP 8.3+ (lokal saat ini PHP 8.4)
- SQLite offline untuk development
- Filament 5.6 untuk admin panel
- Blade + Tailwind CSS untuk frontend
- Spatie Permission untuk role
- Spatie Activity Log, Sitemap, Backup, Sluggable, Image Optimizer
- Artesaos SEO Tools

## Database Offline

Database development menggunakan SQLite:

```text
database/database.sqlite
```

Konfigurasi ada di:

```text
.env
.env.example
```

Nilai penting:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
FILESYSTEM_DISK=public
```

Jika proyek dipindahkan dan file SQLite belum ada:

```powershell
New-Item -ItemType File database/database.sqlite
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
- Unit Kantor Kemenag Tana Toraja
- Kategori berita sesuai rancangan
- Tag awal
- Halaman Profil, Visi Misi, Struktur Organisasi, Kontak, PPID
- Akun Super Admin

## Cara Menjalankan Setelah Dipindah Ke Folder Herd

Masuk ke folder proyek baru, lalu jalankan:

```powershell
composer install
npm install
copy .env.example .env
php artisan key:generate
New-Item -ItemType File database/database.sqlite
php artisan migrate --seed
php artisan storage:link
npm run build
```

Jika file `.env` dan `database/database.sqlite` ikut dipindahkan, biasanya tidak perlu `copy .env.example .env`, `key:generate`, dan `migrate --seed` lagi.

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

## Catatan Penting

Untuk sementara, field `slug` di admin masih perlu diisi manual saat membuat berita/kategori/unit/halaman.

Contoh slug:

```text
berita-kegiatan-kemenag
profil-kantor
kua-makale
```

Langkah berikutnya yang perlu dirapikan:

1. Buat slug otomatis dari judul/nama di form admin.
2. Tambahkan sample berita agar halaman depan tidak kosong.
3. Rapikan role permission per menu.
4. Tambahkan approval workflow untuk Editor dan Kontributor.
5. Tambahkan dashboard statistik berita.
6. Rapikan desain frontend sesuai identitas Kemenag.
