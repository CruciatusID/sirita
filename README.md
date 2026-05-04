# SIRITA

SIRITA adalah aplikasi web berita dan administrasi berbasis Laravel + Filament.

## Yang Perlu Disiapkan

Sebelum menjalankan proyek ini di komputer baru, pastikan sudah ada:

- PHP 8.3 atau lebih baru
- Composer
- Node.js dan npm
- MySQL
- Git

Kalau Anda memakai Herd di Windows, itu juga bisa dipakai. Jika tidak, proyek tetap bisa dijalankan dengan `php artisan serve`.

## Langkah Pertama Saat Clone

Setelah repository di-clone, buka terminal di folder proyek lalu jalankan:

```powershell
composer install
npm install
copy .env.example .env
php artisan key:generate
```

Setelah itu, buka file `.env` dan pastikan bagian database diisi benar.

Contoh:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kemenagt_sirita
DB_USERNAME=root
DB_PASSWORD=
FILESYSTEM_DISK=public
```

## Siapkan Database

Buat database baru di MySQL dengan nama:

```text
kemenagt_sirita
```

Lalu jalankan:

```powershell
php artisan migrate --seed
php artisan storage:link
```

## Jalankan Aplikasi

Untuk development, jalankan dua terminal:

```powershell
php artisan serve
```

```powershell
npm run dev
```

Kalau Anda tidak mau mode development, jalankan build asset sekali saja:

```powershell
npm run build
```

## Login Admin

Buka:

```text
/admin
```

Akun awal:

```text
Email: admin@sirita.local
Password: password
```

## Catatan Penting

- Jika gambar atau file tidak muncul, cek dulu `php artisan storage:link`.
- Jika perubahan tampilan tidak terlihat, jalankan ulang `npm run dev` atau `npm run build`.
- Kalau database kosong, ulangi `php artisan migrate --seed`.

## Ringkasan Perintah Awal

Urutan paling aman untuk instalasi baru:

```powershell
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run build
```

