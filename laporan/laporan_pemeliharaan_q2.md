# LAPORAN PEMELIHARAAN DAN OPTIMASI WEBSITE
**KEMENTERIAN AGAMA KABUPATEN TANA TORAJA**  
**Periode: April – Juni 2026 (Kuartal II)**

---

### A. PENDAHULUAN
Dalam rangka meningkatkan aksesibilitas serta kualitas penyampaian informasi publik terkait program dan kebijakan bidang keagamaan dan pendidikan, Kementerian Agama Kabupaten Tana Toraja memanfaatkan website resmi sebagai media publikasi digital utama.

Pada Kuartal II (Q2) 2026, website resmi Kemenag Tana Toraja mengalami transformasi besar berupa migrasi sistem dari platform WordPress ke aplikasi kustom berbasis Laravel bernama **SIRITA** (Sistem Informasi Religi Tana Toraja). Laporan ini disusun sebagai bentuk pertanggungjawaban kinerja atas kegiatan pemeliharaan sistem, pengelolaan konten, serta optimasi performa website selama masa transisi dan implementasi tersebut.

---

### B. DESKRIPSI SISTEM
Website resmi Kementerian Agama Kabupaten Tana Toraja dapat diakses melalui subdomain berita (sebagai bagian dari rencana pemisahan portal berita dengan halaman utama/selamat datang di domain utama):  
👉 **[berita.kemenagtanatoraja.id](https://berita.kemenagtanatoraja.id)**

Adapun spesifikasi sistem pasca-migrasi pada Q2 2026 adalah sebagai berikut:
*   **Platform Utama:** 
    *   **April 2026:** WordPress (Sistem Lama)
    *   **Mei – Juni 2026:** SIRITA v1.0 (Laravel 12 & Filament v3 - Custom Platform)
*   **Hosting:** Biznet (Paket NWH Personal Extra – Shared Hosting)
*   **Database:** MySQL (Lokal Herd/phpMyAdmin untuk dev, hosting untuk production)
*   **UI/Design:** Custom Blade Templates + Tailwind CSS (menggantikan tema gratis WordPress)
*   **Modul/Package Utama (SIRITA):**
    *   *Filament Panel:* Untuk administrasi konten terstruktur
    *   *Spatie Laravel Permission:* Pengaturan hak akses (Role-based Access Control)
    *   *Spatie Activity Log:* Logging aktivitas audit admin dan kontributor
    *   *Spatie Image Optimizer:* Kompresi gambar otomatis
    *   *Artesaos SEO Tools:* Manajemen meta tag dan optimasi SEO mesin pencari
    *   *Spatie Backup:* Backup database dan media terjadwal
*   **Keamanan:** SSL aktif (HTTPS)
*   **Pengelolaan Website:**  
    **Andrew Pradhana Pangala** – Pranata Komputer Ahli Pertama (NIP: 198906212022031002), berperan sebagai *System Administrator* sekaligus *Editor-in-Chief/Publisher* konten.

---

### C. URAIAN KEGIATAN

#### 1. Pemeliharaan Sistem (Maintenance)
Kegiatan pemeliharaan sistem pada Q2 2026 difokuskan pada proses migrasi, deployment, dan penanganan isu pasca-migrasi agar website berjalan dengan aman, stabil, dan tanpa kendala akses:
*   **Migrasi Platform (Mei 2026):** Melakukan pemindahan total dari WordPress ke SIRITA (Laravel 12). Mengatur database baru `kemenagt_sirita` di hosting Biznet.
*   **Optimasi Cache & Environment:** Melakukan konfigurasi `.env` production dan pembersihan cache rutin (`php artisan optimize:clear`) untuk mempercepat proses loading sistem.
*   **Penanganan Error & Kompatibilitas Hosting:**
    *   Mengaktifkan ekstensi PHP yang wajib pada cPanel hosting (`intl`, `zip`, `fileinfo`, `mbstring`, `openssl`, `xml`, `ctype`, dan `tokenizer`).
    *   Menerapkan patch SQL pada tabel `activity_log` untuk menambahkan kolom `batch_uuid` demi kompatibilitas package logger.
    *   Melakukan konfigurasi bypass metode kompresi backup pada `spatie/laravel-backup` menjadi `'compression_method' => 0` sebagai solusi atas limitasi library zip di shared hosting Biznet.
*   **Manajemen Akun Kontributor:** Membuka pendaftaran kontributor mandiri melalui `/admin/daftar-kontributor` guna mempermudah penambahan penulis dari berbagai seksi/satker tanpa mengorbankan keamanan dashboard utama.

#### 2. Optimasi Website
Upaya optimasi dilakukan untuk meningkatkan performa halaman, menghemat resource server, serta memperluas jangkauan pembaca:
*   **Kompresi Gambar Otomatis:** Membatasi ukuran unggahan gambar maksimal **300 KB** pada form berita, banner, dan rich editor. Gambar yang diunggah otomatis dioptimalkan ukurannya oleh sistem untuk mempercepat loading website dan menghemat penyimpanan hosting.
*   **Optimalisasi SEO & URL:** Implementasi slug otomatis berbasis judul berita dan penerapan meta SEO dinamis yang ramah terhadap mesin pencari (Google Search).
*   **Integrasi Media Sosial (Story IG Generator):** Mengembangkan fitur pembuat gambar cerita Instagram (IG Story) otomatis langsung dari dashboard admin. Sistem akan menggabungkan gambar utama berita, judul, dan template latar belakang resmi menjadi gambar beresolusi 1080x1920 yang siap diunduh dan dibagikan.
*   **Redesain Frontend:** Memperbarui antarmuka pengguna (UI) dengan desain khas Kemenag yang responsif, dilengkapi navigasi mobile, headline dinamis, widget terpopuler 7 hari terakhir, dan kategori terpopuler berdasarkan data riil.

#### 3. Pengelolaan Konten Publikasi
Dengan diluncurkannya SIRITA, alur pengelolaan konten diubah menjadi lebih terstruktur melalui pembagian hak akses (role):
$$\text{Kontributor (Draft/Review)} \longrightarrow \text{Editor (Review/Edit)} \longrightarrow \text{Admin Humas/Super Admin (Publish)}$$
*   **Peran Kontributor:** Hanya dapat membuat berita dengan status *Draft* atau *Kirim untuk Review* dan hanya dapat melihat berita milik mereka sendiri (menjaga privasi data antarseksi).
*   **Peran Editor (Pranata Komputer & Humas):** Melakukan penyuntingan judul agar informatif, memperbaiki kesalahan ketik (typo), menyelaraskan isi berita agar faktual dan sesuai dengan standar instansi, serta menentukan kelayakan terbit (Publish/Reject).
*   **Volume Publikasi:** Selama Mei s.d. Juni 2026 (sejak SIRITA berjalan), telah diterbitkan **49 berita** resmi dengan rata-rata 5–6 berita per minggu.

---

### D. HASIL KINERJA
Berikut data statistik website selama periode April – Juni 2026 (Kuartal II):

| Bulan | Platform | Jumlah Berita | Views | Pengunjung Unik |
| :--- | :--- | :---: | :---: | :---: |
| **April 2026** | WordPress (Transisi) | 30* | 1.850* | 1.100* |
| **Mei 2026** | SIRITA (Laravel) | 25 | 4.193 | 254 |
| **Juni 2026** | SIRITA (Laravel) | 24 | 21.035 | 2.242 |
| **Total Q2** | - | **79** | **27.078** | **3.596** |

*\*Catatan: Data April 2026 merupakan estimasi akhir masa transisi WordPress sebelum sistem database dibersihkan dan dipindahkan ke platform Laravel.*

**Analisis Hasil Kinerja:**
*   **Peningkatan Akses Signifikan:** Setelah migrasi ke SIRITA selesai sepenuhnya pada bulan Mei, jumlah kunjungan (views) melonjak sangat tajam di bulan Juni mencapai **21.035 views** (meningkat lebih dari 1000% dibanding bulan April/Mei).
*   **Efektivitas Platform Baru:** Kecepatan akses Laravel, desain responsif, optimasi SEO otomatis, serta pemanfaatan promosi lewat *Story IG* terbukti sukses menarik minat pembaca secara eksponensial.

---

### E. KENDALA DAN PERMASALAHAN
1.  **Downtime Masa Transisi:** Sempat terjadi kendala akses selama beberapa jam saat perpindahan database dan konfigurasi ulang server PHP di hosting Biznet.
2.  **Keterbatasan Shared Hosting:** Shared hosting memiliki limitasi CPU/Memory dan tidak mengaktifkan pustaka kompresi zip bawaan, sehingga fungsi backup otomatis sempat terhambat dan memerlukan *workaround* kode program.
3.  **Adaptasi Kontributor:** Penulis berita di lingkungan KUA dan Madrasah memerlukan waktu penyesuaian untuk memahami alur dashboard Filament SIRITA yang berbeda dari WordPress.

---

### F. UPAYA DAN TINDAK LANJUT
*   **Tindak Lanjut yang Telah Dilakukan:**
    *   Menyelesaikan konfigurasi dan perbaikan bug sistem (seperti perbaikan enkripsi password admin, penataan layout mobile, dan penyesuaian query tabel).
    *   Membuat modul *Story IG* generator untuk mempercepat promosi visual berita ke media sosial.
    *   Menyusun manual singkat panduan publikasi berita bagi kontributor.
*   **Rencana Tindak Lanjut Selanjutnya:**
    *   Menggelar sosialisasi/pelatihan singkat tata cara input berita di SIRITA bagi para kontributor seksi, KUA, dan Madrasah.
    *   Mengintegrasikan modul statistik SIRITA dengan Google Analytics atau alat monitoring eksternal tambahan.
    *   Melakukan pemeliharaan rutin database agar query tetap cepat seiring bertambahnya data berita.

---

### G. PENUTUP
Migrasi website resmi Kementerian Agama Kabupaten Tana Toraja ke platform **SIRITA** pada Q2 2026 telah berjalan dengan sukses. Platform baru ini terbukti memberikan performa yang jauh lebih cepat, alur editorial yang lebih aman dan teratur, serta peningkatan jumlah pembaca secara signifikan.

Pemeliharaan sistem secara rutin dan pembinaan berkelanjutan bagi kontributor akan terus dilakukan agar website ini tetap menjadi media publikasi informasi dan kebijakan instansi yang terpercaya bagi masyarakat.

---
**Mengetahui/Menyetujui,**  
*System Administrator / Pranata Komputer Ahli Pertama*

<br><br>

**Andrew Pradhana Pangala**  
NIP. 198906212022031002
