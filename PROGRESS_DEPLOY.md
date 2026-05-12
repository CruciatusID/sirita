# Progress Deploy SIRITA

Tanggal: 2026-05-12

## Status Terakhir

- Stack project sudah diturunkan ke PHP 8.2 dan Laravel 12.
- Login admin sudah diubah dari email menjadi username.
- Email user dibuat opsional untuk kebutuhan SMTP di masa depan.
- SQL awal untuk phpMyAdmin sudah dibuat di `database/sql/kemenagt_berita_import.sql`.
- Zip deploy untuk hosting sudah dibuat lokal di `_deploy/sirita-hosting-upload.zip`.
- Hosting diarahkan ke `public_html/sirita/public`.

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
SET `password` = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi'
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
