# e-TOEFL Ma'had Aly Hasyim Asy'ari Tebuireng

[![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=for-the-badge&logo=vue.js)](https://vuejs.org)
[![Inertia.js](https://img.shields.io/badge/Inertia.js-1.x-9553E9?style=for-the-badge&logo=inertia)](https://inertiajs.com)
[![Tailwind CSS](https://img.shields.io/badge/TailwindCSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css)](https://tailwindcss.com)
[![Cloudflare Turnstile](https://img.shields.io/badge/Cloudflare-Turnstile-F38020?style=for-the-badge&logo=cloudflare)](https://www.cloudflare.com)
[![Brevo](https://img.shields.io/badge/Brevo-Email%20API-0B996F?style=for-the-badge)](https://www.brevo.com)

Sistem Ujian Standarisasi Bahasa Inggris Daring (e-TOEFL & e-TOEIC) resmi **Ma'had Aly Hasyim Asy'ari Pondok Pesantren Tebuireng Jombang**. Aplikasi ini dirancang modern, aman, dan responsif untuk memfasilitasi pelaksanaan ujian TOEFL secara daring dengan fitur Listening Audio, Bank Soal Terstruktur, Anti-Cheat Cerdas, Kalkulasi Skor Otomatis Standar TOEFL PBT (310–677), serta Penerbitan e-Sertifikat Digital instan ber-QR Code validasi resmi.

---

## 🏛️ Identitas Lembaga & Branding

- **Lembaga:** Ma'had Aly Hasyim Asy'ari Pondok Pesantren Tebuireng
- **Website Utama:** [mahatbi.or.id](https://mahatbi.or.id)
- **Domain Ujian (Produksi):** [etoefl.mahatbi.or.id](https://etoefl.mahatbi.or.id)
- **Alamat:** Jl. Irian Jaya No. 10, Tebuireng, Cukir, Kec. Diwek, Kabupaten Jombang, Jawa Timur 61471
- **Email Lembaga:** `mahadaly@tebuireng.ac.id` / `admin@etoefl.mahatbi.or.id`
- **Tanda Tangan Sertifikat:** **Abdul Karim Amrulloh** (Mudir Ma'had Aly Hasyim Asy'ari)

---

## 🌟 Fitur Utama Sistem

1. **Autentikasi Modern & Aman:**
   - Registrasi berbasis email murni (*email-only registration*).
   - Login 1-Klik dengan Akun Google (*Google OAuth 2.0 via Laravel Socialite*).
   - Perlindungan anti-bot & spam cerdas menggunakan **Cloudflare Turnstile** (tanpa puzzle CAPTCHA rumit).
   - Reset password berbasis link aman via email (Brevo API).

2. **Mesin Ujian e-TOEFL & e-TOEIC:**
   - 3 Sesi Ujian Standar: *Listening Comprehension*, *Structure & Written Expression*, dan *Reading Comprehension*.
   - Pemutar Audio Listening dengan proteksi pembatasan putar & encoding nama file aman.
   - *Optimistic UI Update* di halaman ujian (jawaban tersimpan instan tanpa lag/freeze).
   - Pengecualian CSRF aman untuk endpoint pengerjaan ujian berdurasi panjang.

3. **Mekanisme Anti-Cheat Cerdas:**
   - Deteksi perpindahan tab / jendela browser (*Page Visibility API* & *Window Blur*).
   - Sistem toleransi peringatan (maksimal 3x toleransi) dengan popup modal peringatan.
   - Perlindungan *anti-false-positive*: toleransi tidak berkurang akibat refresh halaman atau gangguan jaringan sementara.
   - Fitur pembukaan blokir peserta (*Unblock*) langsung dari panel Administrator.

4. **Kalkulasi Skor & E-Sertifikat Otomatis:**
   - Konversi tabel skor standar TOEFL PBT (skala 310 - 677).
   - Template e-Sertifikat PDF presisi 1 halaman penuh (A4 Landscape, DomPDF).
   - Dilengkapi identitas resmi Ma'had Aly Tebuireng: logo lingkaran hijau resmi, watermark transparan 6%, tanda tangan Mudir *Abdul Karim Amrulloh*, dan stempel kelembagaan.
   - Verifikasi keaslian via QR Code dinamis (`/certificate/verify/{code}`).

5. **Panel Admin & Manajemen Soal:**
   - Dashboard analitik peserta, transaksi, dan riwayat ujian.
   - Manajemen Bank Soal dengan editor WYSIWYG Summernote dan upload audio listening.
   - Manajemen Paket Ujian (berbayar maupun paket gratis Rp 0 yang otomatis aktif tanpa konfirmasi manual).

---

## 🚀 Panduan Setup & Instalasi (Local Development)

### 1. Kebutuhan Sistem (Prerequisites)
- PHP `>= 8.1` s/d `8.3`
- Ekstensi PHP wajib: `curl`, `mbstring`, `openssl`, `pdo_mysql`, `gd`, `zip`, `fileinfo`
- MySQL / MariaDB `>= 5.7`
- Composer `>= 2.x`
- Node.js `>= 18.x` & NPM `>= 9.x`
- Laragon / XAMPP di Windows, atau Valet / Docker di macOS/Linux

### 2. Langkah Instalasi Langkah-demi-Langkah

```bash
# 1. Clone repository
git clone https://github.com/ArindanAditiya/e-toefl.git
cd e-toefl

# 2. Install dependensi PHP
composer install

# 3. Salin file environment & konfigurasi
cp .env.example .env

# 4. Generate Application Key
php artisan key:generate

# 5. Konfigurasi database di file .env
# DB_DATABASE=toefl
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Jalankan migrasi dan seeder database
php artisan migrate --seed

# 7. Buat symbolic link untuk folder storage (Audio & Gambar)
php artisan storage:link

# 8. Install dependensi frontend dan compile asset
npm install
npm run build
```

---

## 🔐 Konfigurasi Environment (`.env`)

Berikut adalah parameter utama yang perlu disesuaikan pada file `.env`:

| Parameter | Keterangan | Nilai Contoh (Local / Trial) |
|---|---|---|
| `APP_NAME` | Nama aplikasi | `"e-TOEFL Ma'had Aly Tebuireng"` |
| `APP_ENV` | Environment kerja | `local` (pengujian) / `production` |
| `APP_DEBUG` | Debug mode | `true` (local) / `false` (production) |
| `APP_URL` | Domain akses | `http://toefl.test` atau `https://trial-toefl.maul.my.id` |
| `BREVO_ACTIVE` | Sakelar Email Brevo | `false` (local) / `true` (live) |
| `BREVO_API_KEY` | API Key Brevo v3 | `xkeysib-...` |
| `TURNSTILE_SITE_KEY` | Site Key Cloudflare | Kunci dari Cloudflare Dashboard |
| `TURNSTILE_SECRET_KEY` | Secret Key Cloudflare | Kunci rahasia Cloudflare |
| `GOOGLE_CLIENT_ID` | OAuth Client ID Google | `*.apps.googleusercontent.com` |
| `GOOGLE_CLIENT_SECRET` | OAuth Client Secret Google | Kunci rahasia dari Google Cloud Console |
| `GOOGLE_REDIRECT_URI` | Callback Google OAuth | `${APP_URL}/auth/google/callback` |

> [!TIP]
> **Cloudflare Turnstile Testing Keys:**
> Jika menguji di lingkungan localhost tanpa internet, gunakan kunci testing resmi Cloudflare:
> - `TURNSTILE_SITE_KEY`: `1x00000000000000000000AA` (Always Passes)
> - `TURNSTILE_SECRET_KEY`: `1x00000000000000000000000000000000BB` (Always Passes)

---

## 📋 Checklist 3 Tahapan Deployment

### Tahap 1: Pra-Development / Setup Awal
- [x] PHP 8.1+ dan ekstensi terinstal.
- [x] Database migration dan seeder role/permissions selesai.
- [x] Folder storage terhubung (`php artisan storage:link`).
- [x] Folder permission `storage/` dan `bootstrap/cache/` dapat ditulisi (writable).

### Tahap 2: Development & Trial Testing
- [x] Domain local (`http://toefl.test`) dan Tunnel (`https://trial-toefl.maul.my.id`) aktif.
- [x] Login email dan Google OAuth berjalan lancar.
- [x] Cloudflare Turnstile tervalidasi sukses di sisi backend.
- [x] Ujian simulasi & pemutar audio listening lancar tanpa error AJAX.
- [x] Transaksi paket ujian gratis (Rp 0) langsung aktif seketika.
- [x] E-Sertifikat PDF teruji pas 1 halaman landscape A4 dengan tanda tangan Mudir.

### Tahap 3: Publikasi ke Produksi (`https://etoefl.mahatbi.or.id`)
- [ ] Ubah `APP_ENV=production` dan `APP_DEBUG=false` di server produksi.
- [ ] Aktifkan `BREVO_ACTIVE=true` dengan domain sender resmi terverifikasi.
- [ ] Pastikan DNS / Cloudflare Tunnel mengarah ke IP / instance server produksi.
- [ ] Jalankan optimasi cache Laravel:
  ```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```

---

## 🔑 Kredensial Administrator Default (Pengujian)

- **URL Login:** `http://toefl.test/login` (atau `/admin/dashboard`)
- **Email:** `admin@mahatbi.or.id`
- **Password:** `AdminTebuireng#2026`

---

## 📚 Dokumentasi Terkait

Untuk pemahaman teknis lebih mendalam, silakan baca file dokumentasi berikut:
1. [`PROJECT_BLUEPRINT.md`](file:///E:/laragon/www/toefl/PROJECT_BLUEPRINT.md) — Cetak biru arsitektur, rekam jejak sprint, dan perbaikan bug sistem.
2. [`PANDUAN_SISTEM_DAN_BANK_SOAL.md`](file:///E:/laragon/www/toefl/PANDUAN_SISTEM_DAN_BANK_SOAL.md) — Panduan lengkap operasional admin, alur ujian, dan input bank soal.
3. [`AUDIT_DAN_RIWAYAT_PERUBAHAN.md`](file:///E:/laragon/www/toefl/AUDIT_DAN_RIWAYAT_PERUBAHAN.md) — Laporan riwayat audit teknis dan daftar file yang dimodifikasi.

---

**Hak Cipta © 2026 Ma'had Aly Hasyim Asy'ari Tebuireng Jombang.**  
*All Rights Reserved.*
