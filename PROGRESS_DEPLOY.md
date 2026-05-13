# Progress Deploy SIRITA

Tanggal: 2026-05-12

## Status Terakhir

- Stack project sudah diturunkan ke PHP 8.2 dan Laravel 12.
- Login admin sudah diubah dari email menjadi username.
- Email user dibuat opsional untuk kebutuhan SMTP di masa depan.
- SQL awal untuk phpMyAdmin sudah dibuat di `database/sql/kemenagt_berita_import.sql`.
- Zip deploy untuk hosting sudah dibuat lokal di `_deploy/sirita-hosting-upload.zip`.
- Hosting diarahkan ke `public_html/sirita/public`.

## Update 2026-05-13

- Login admin production berhasil setelah hash bcrypt admin diperbaiki di database hosting.
- Extension hosting yang wajib dipastikan aktif: `intl`, `zip`, `fileinfo`, `mbstring`, `openssl`, `xml`, `ctype`, dan `tokenizer`. `pdo_mysql skipped as conflicting` aman selama aplikasi sudah bisa query MySQL.
- Struktur `activity_log` hosting perlu kolom `batch_uuid` untuk kompatibilitas Spatie Activitylog versi terpasang.
- Model Eloquent diubah dari PHP attribute `#[Fillable]`/`#[Hidden]` ke properti standar `$fillable`/`$hidden` agar mass assignment stabil di hosting.
- Method activity log di `Post` disesuaikan dari `dontLogEmptyChanges()` ke `dontSubmitEmptyLogs()`.
- Alur berita diperbaiki:
  - Kontributor hanya bisa `Draft` atau `Kirim untuk Review`.
  - Kontributor hanya melihat berita miliknya sendiri.
  - Editor tidak melihat draft kontributor; editor baru melihat berita status `review`, `published`, atau `rejected`.
  - Super Admin dan Admin Humas tetap melihat semua berita.
- Dashboard ditambah ringkasan role:
  - Kontributor melihat jumlah draft, review, terbit, dan ditolak miliknya.
  - Editor melihat antrean review, terbit, dan ditolak.
  - Antrean review tersedia untuk role editorial.
- Halaman `Profil Saya` ditambahkan untuk semua user agar bisa ubah nama tampilan, email, dan password sendiri.
- Form pengguna menambahkan `Konfirmasi Password`.
- Registrasi kontributor via URL langsung ditambahkan di `/admin/daftar-kontributor`; tidak ada tombol daftar di halaman login.
- Bug Firefox di dashboard setelah navigasi balik dari menu lain ditangani dengan reset scroll khusus Firefox pada URL `/admin`.
- Artifact zip lokal di `_deploy/` dibuat hanya untuk upload manual hosting dan tidak dimasukkan ke git.

## Login Admin Awal

```text
Username: admin
Password: password
```

Jika muncul error:

```text
This password does not use the Bcrypt algorithm.
```

jalankan SQL ini di phpMyAdmin:

```sql
UPDATE `users`
SET `password` = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    `status` = 'active',
    `updated_at` = NOW()
WHERE `username` = 'admin';
```

Lalu cek:

```sql
SELECT `id`, `username`, `password`, LENGTH(`password`) AS password_length
FROM `users`
WHERE `username` = 'admin';
```

Nilai benar:

```text
password diawali: $2y$10$
password_length: 60
```

## SQL Patch Hosting 2026-05-13

Jika activity log error `Unknown column 'batch_uuid'`, jalankan:

```sql
ALTER TABLE `activity_log`
ADD COLUMN `batch_uuid` CHAR(36) NULL AFTER `properties`;
```

Jika tabel `posts` belum sesuai kode terbaru, pastikan kolom berikut ada:

```sql
ALTER TABLE `posts`
ADD COLUMN `featured_image_caption` VARCHAR(255) NULL AFTER `featured_image`;

ALTER TABLE `posts`
ADD COLUMN `editor_user_id` BIGINT UNSIGNED NULL AFTER `user_id`;

ALTER TABLE `posts`
ADD COLUMN `editor_name` VARCHAR(255) NULL AFTER `editor_user_id`;

ALTER TABLE `posts`
ADD COLUMN `likes_count` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `views`;

ALTER TABLE `posts`
ADD COLUMN `shares_count` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `likes_count`;
```

Jika kolom sudah ada, phpMyAdmin akan menampilkan `Duplicate column name`; itu bisa diabaikan untuk kolom tersebut.

## Catatan Hosting

- PHP hosting sudah terdeteksi PHP 8.2.30.
- Error 500 sebelumnya disebabkan extension `zip` tidak aktif:

```text
Class "ZipArchive" not found
```

Solusi terbaik: aktifkan PHP extension `zip` dari panel hosting.

Workaround sementara jika extension `zip` tidak tersedia:

Edit:

```text
public_html/sirita/vendor/spatie/laravel-backup/config/backup.php
```

Ganti:

```php
'compression_method' => ZipArchive::CM_DEFAULT,
```

menjadi:

```php
'compression_method' => 0,
```

## .env Hosting

Pastikan `.env` hosting tidak memakai placeholder:

```env
APP_NAME="SIRITA"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://berita.kemenagtanatoraja.id

LOG_CHANNEL=single
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=kemenagt_berita
DB_USERNAME=ISI_USERNAME_DATABASE_ASLI
DB_PASSWORD=ISI_PASSWORD_DATABASE_ASLI

FILESYSTEM_DISK=public
FILESYSTEM_PUBLIC_ROOT=public/storage
```

Untuk troubleshooting sementara:

```env
APP_ENV=local
APP_DEBUG=true
LOG_LEVEL=debug
```

Setelah normal, kembalikan:

```env
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=error
```

## Permission Folder

Folder harus writable:

```text
storage/
bootstrap/cache/
public/storage/
```

Rekomendasi awal:

```text
Folder: 755 atau 775
File: 644
```

Jika masih gagal menulis cache/log, pakai 777 sementara untuk troubleshooting, lalu turunkan lagi.

## File Deploy

Zip deploy lokal:

```text
_deploy/sirita-hosting-upload.zip
```

Isi zip diekstrak langsung ke:

```text
public_html/sirita/
```

Set domain/subdomain ke:

```text
public_html/sirita/public
```

## Catatan Perubahan Kode Belum Diuji Penuh di Hosting

- `config/filesystems.php` ditambah `FILESYSTEM_PUBLIC_ROOT`, supaya upload file bisa langsung ke `public/storage` tanpa perlu `php artisan storage:link`.
- Custom login page ada di `app/Filament/Pages/Auth/Login.php`.
- Provider panel admin memakai `ValidateCsrfToken` untuk kompatibilitas Laravel 12.
- Package `spatie/laravel-backup` masih ada. Jika hosting tidak menyediakan extension `zip`, sebaiknya nanti package backup dihapus dari project agar tidak perlu edit vendor.
