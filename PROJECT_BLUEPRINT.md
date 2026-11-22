# PROJECT BLUEPRINT: e-TOEFL MA'HAD ALY HASYIM ASY'ARI TEBUIRENG
**Dokumen Acuan Audit Arsitektur, Bug Fixing, Migrasi Brevo, dan Re-Branding**
*Tanggal Pembuatan:* 22 November 2026  
*Domain Produksi (Cloudflare Tunnel):* `https://etoefl.mahatbi.or.id/id/index.php`  
*Domain Lokal (Laragon):* `http://toefl.test/` (dan `http://e-toefl.test/`)  
*Instansi Pemilik:* Ma'had Aly Hasyim Asy'ari Pondok Pesantren Tebuireng Jombang (`mahatbi.or.id`)

---

## 1. STRUKTUR DIREKTORI & ENTRY POINT APLIKASI

### 1.1 Struktur Folder Proyek
```
E:\laragon\www\toefl/ (dan E:\laragon\www\e-toefl/)
├── app/
│   ├── Actions/Fortify/CreateNewUser.php      # Registrasi & trigger aktivasi
│   ├── Helpers/Helpers.php                    # Helper global (sendWhatsappNotification via Fonnte)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ActivationController.php       # Aktivasi & Reset Password
│   │   │   ├── Admin/User/UserController.php  # Reminder aktivasi & member
│   │   │   └── User/TryOut/ExamController.php # Inti pengerjaan ujian & listening
│   │   └── Middleware/
│   │       ├── HandleInertiaRequests.php      # Sharing data admin kontak ke frontend Vue
│   │       └── TrustProxies.php               # Konfigurasi reverse proxy / Cloudflare
│   ├── Notifications/
│   │   ├── SendNewUserNotification.php        # Notifikasi user baru ke admin (sebelumnya Telegram)
│   │   └── SendTransactionNotification.php    # Notifikasi transaksi ke admin (sebelumnya Telegram)
│   ├── Providers/AppServiceProvider.php       # Service bindings & SSL enforcement
│   └── Services/Transaction/TransactionService.php # Logika transaksi & kirim token ujian
├── config/
│   ├── mail.php                               # Konfigurasi driver email Laravel
│   └── services.php                           # Konfigurasi kontak admin, Telegram, WhatsApp
├── public/
│   ├── assets/                                # CSS, JS, Fonts, Certificate templates
│   ├── build/                                 # Vite compiled assets
│   ├── id/                                    # Landing Page publik (/id/index.php)
│   │   ├── assets/                            # TemplateMo Space Dynamic assets
│   │   └── index.php                          # Entry point landing page utama
│   ├── storage/                               # Symlink ke storage/app/public (audio, gambar, logo)
│   └── index.php                              # Entry point utama Laravel framework
├── resources/
│   ├── js/
│   │   ├── app.js                             # Inisialisasi Vue 3, mixin data kontak default
│   │   ├── Components/                        # Header, Footer, Sidebar (Admin & User)
│   │   └── Pages/                             # Halaman Inertia Vue (Auth, Exam, Dashboard, dll)
│   └── views/
│       ├── app.blade.php                      # Root layout Blade untuk Inertia
│       └── certificate/                       # Template sertifikat PDF (DomPDF)
├── routes/
│   └── web.php                                # Alur routing web (/ me-redirect ke /id/index.php)
└── .env & .env.example                        # Konfigurasi environment (kredensial & kontak)
```

### 1.2 Titik Masuk (Entry Points) & Alur Routing
1. **Root URL (`/`)**: 
   - Ditangani oleh `routes/web.php` baris 22–24:
     ```php
     Route::get('/', function () {
         return redirect('/id/index.php');
     })->name('home');
     ```
2. **Landing Page Publik (`/id/index.php`)**:
   - File mandiri PHP di `public/id/index.php` menggunakan template Space Dynamic.
   - Membaca konfigurasi `.env` secara dinamis (`APP_NAME`, `ADMIN_WA`, `APP_URL`).
   - Berisi navigasi ke `/login`, `/register`, penjelasan TOEFL/TOEIC, showcase sertifikat digital, dan form kontak.
3. **Aplikasi Ujian & Autentikasi (Laravel + Inertia + Vue 3)**:
   - `/login`, `/register`, `/user/forgot-password` untuk siswa.
   - `/user/dashboard`, `/user/try-out/*` untuk pengerjaan soal dan pemutaran audio listening.
   - `/admin/*` untuk manajemen bank soal, kategori nilai, user, dan transaksi.

---

## 2. AUDIT & IDENTIFIKASI AKAR MASALAH (ROOT CAUSE)

### 2.1 Bug Lokal: `Fatal error: Failed opening required '.../vendor/autoload.php'`
- **Gejala:** Akses ke `http://e-toefl.test/` menghasilkan error Fatal pada `public/index.php` baris 34.
- **Akar Masalah:**
  - Laragon membaca direktori `E:\laragon\www\e-toefl` dan secara otomatis membuat virtual host `auto.e-toefl.test.conf` yang mengarah ke `E:/laragon/www/e-toefl/public`.
  - Folder `e-toefl` merupakan clone git dari repositori GitHub, namun di folder tersebut **belum pernah dijalankan `composer install`** sehingga folder `vendor/` dan file `.env` tidak ada.
  - Sedangkan direktori `E:\laragon\www\toefl` adalah direktori kerja aktif yang sudah memiliki `vendor/`, `node_modules/`, dan file `.env`, yang melayani `http://toefl.test/`.
- **Solusi:**
  - Buat junction/symlink atau salin `vendor/` dan `.env` dari `toefl` ke `e-toefl`, atau satukan git tracking agar kedua domain lokal berjalan konsisten tanpa error autoload.

### 2.2 Masalah Deprecations PHP 8.4
- **Gejala:** Laragon menjalankan PHP 8.4.27 yang memicu ratusan pesan `Deprecated: Implicitly marking parameter $... as nullable is deprecated...` pada vendor package lama sebelum header dikirim.
- **Dampak:** Redirect HTTP 302 dari Laravel terblokir karena *headers already sent*, sehingga fallback ke HTML meta refresh yang lambat dan mengotori log.
- **Solusi:** Tambahkan penanganan `error_reporting(E_ALL & ~E_DEPRECATED);` pada entry point (`public/index.php` dan `bootstrap/app.php`).

### 2.3 Masalah Cloudflare Tunnel (`https://etoefl.mahatbi.or.id`)
- **Gejala:** Broken links, redirect loop ke HTTP, dan mixed content blocking pada aset CSS/JS/Audio.
- **Akar Masalah:**
  - Cloudflare Tunnel melakukan SSL termination di edge dan meneruskan trafik ke origin via HTTP.
  - Jika Laravel tidak secara tegas mendeteksi SSL reverse proxy (`X-Forwarded-Proto: https`), method `url()`, `asset()`, dan `redirect()` menghasilkan protokol `http://`. Browser memblokir aset HTTP di halaman HTTPS (*Mixed Content*).
- **Solusi:**
  - Pastikan `TrustProxies.php` mempercayai header Cloudflare (`$proxies = '*'`).
  - Tambahkan pemaksaan skema HTTPS (`URL::forceScheme('https')`) di `AppServiceProvider.php` saat berada di lingkungan produksi atau jika header `HTTP_X_FORWARDED_PROTO == 'https'`.
  - Pastikan path aset di `public/id/index.php` menggunakan path absolut atau basis URL yang benar sehingga aman diakses dengan atau tanpa trailing slash (`/id` vs `/id/index.php`).

---

## 3. PETA FILE NOTIFIKASI & RENCANA MIGRASI BREVO EMAIL API

### 3.1 Inventaris File Notifikasi Saat Ini (Eksisting)

| No | File Pemanggil | Penerima | Channel Lama | Konten Notifikasi |
|---|---|---|---|---|
| 1 | `app/Actions/Fortify/CreateNewUser.php` (L:80-84) | Siswa | WhatsApp (Fonnte API) | Link aktivasi akun siswa setelah registrasi |
| 2 | `app/Actions/Fortify/CreateNewUser.php` (L:83-84) | Admin | Telegram Bot (`SendNewUserNotification`) | Info registrasi user baru (nama, email, tanggal) |
| 3 | `app/Http/Controllers/ActivationController.php` (L:57-58) | Siswa | WhatsApp (Fonnte API) | Reset password acak baru |
| 4 | `app/Http/Controllers/Admin/User/UserController.php` (L:162-164) | Siswa | WhatsApp (Fonnte API) | Reminder aktivasi akun siswa |
| 5 | `app/Http/Controllers/Admin/User/UserController.php` (L:175-177) | Siswa | WhatsApp (Fonnte API) | Reminder aktivasi member & panduan tryout |
| 6 | `app/Services/Transaction/TransactionService.php` (L:89-102) | Siswa | WhatsApp (Fonnte API) | Status transaksi: Paid, Failed, Done (Kirim Token Voucher) |
| 7 | `app/Services/Transaction/TransactionService.php` (L:137-143) | Siswa | WhatsApp (Fonnte API) | Order invoice pesanan ujian baru (Free / Paid) |
| 8 | `app/Services/Transaction/TransactionService.php` (L:145-146) | Admin | Telegram Bot (`SendTransactionNotification`) | Laporan transaksi baru (kode, nama, email, nominal) |
| 9 | `app/Helpers/Helpers.php` (L:74-107) | - | cURL Fonnte API (`api.fonnte.com/send`) | Fungsi inti `sendWhatsappNotification()` |

### 3.2 Desain Arsitektur Notifikasi Baru (Brevo Email API)
- Menghentikan ketergantungan pada sewa API WhatsApp berbayar (Fonnte) dan bot Telegram.
- Membangun service dedicated: `App\Services\Notification\BrevoEmailService` yang:
  - Menggunakan **Brevo REST API v3** (`https://api.brevo.com/v3/smtp/email`) via Guzzle HTTP Client bila `BREVO_API_KEY` terisi di `.env`.
  - Fallback ke **Laravel Mailer (SMTP Brevo / Log)** menggunakan konfigurasi standard `MAIL_HOST=smtp-relay.brevo.com`, `MAIL_PORT=587`, dll.
  - Template email HTML bergaya profesional bernuansa islami-akademik Ma'had Aly Tebuireng.
- Mengubah notification channel `SendNewUserNotification` dan `SendTransactionNotification` dari `['telegram']` menjadi `['mail']` dengan layout MailMessage yang rapi dan elegan.
- Memperbarui `sendWhatsappNotification()` di `Helpers.php` agar menjadi bridge/adapter ke `BrevoEmailService` sehingga setiap controller yang sebelumnya memanggil WhatsApp otomatis mengirim email tanpa merusak backward-compatibility kode legacy.

### 3.3 Variabel Konfigurasi `.env` & `.env.example`
```env
# BREVO EMAIL API CONFIGURATION
BREVO_API_KEY=
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="mahadaly@tebuireng.ac.id"
MAIL_FROM_NAME="e-TOEFL Ma'had Aly Hasyim Asy'ari Tebuireng"
```

---

## 4. PEMBARUAN INFORMASI KONTAK RESMI & IDENTITAS MA'HAD ALY

### 4.1 Data Kontak Resmi
- **WhatsApp Support:** Mendukung format username WhatsApp `@username` atau link `https://wa.me/...`
  - Konfigurasi Default: `ADMIN_WA="@mahadalytbi"` (atau tautan wa.me resmi)
  - Di landing page dan halaman bantuan, sistem memvalidasi jika input diawali `@` maka diarahkan ke link profil `https://wa.me/` yang sesuai.
- **Email Resmi:** `mahadaly@tebuireng.ac.id`
- **Alamat Resmi:** `Jl. Irian Jaya No. 10, Tebuireng, Cukir, Kec. Diwek, Kabupaten Jombang, Jawa Timur 61471`
- **File Terdampak:**
  - `.env` & `.env.example`
  - `config/services.php`
  - `resources/js/app.js` (global mixin: `appName`, `wa`, `copyright`, `email`, `alamat`)
  - `public/id/index.php` (landing page contact section & floating WA button)
  - `resources/js/Components/LayoutAdmin/Footer.vue`
  - `resources/js/Components/LayoutUser/Footer.vue`
  - `resources/js/Pages/Admin/Transaction/Transaction/Invoice.vue`
  - `resources/js/Pages/User/Transaction/Transaction/Show.vue`

---

## 5. RE-BRANDING, TEMA WARNA, & SEO/META MA'HAD ALY TEBUIRENG

### 5.1 Standar Warna Akademik Pesantren Tebuireng
| Elemen | Warna | Kode Hex |
|---|---|---|
| Hijau Tua (Header / Deep Accent) | Dark Forest Green | `#0A3D24` |
| Hijau Utama (Primary Buttons / Branding) | Pesantren Green | `#007A1E` |
| Putih Bersih (Background / Content Canvas) | Clean White | `#FFFFFF` |
| Aksen Emas (Highlight / Sub-Heading / Badges) | Golden Ochre | `#C69C2B` |

### 5.2 Eliminasi AI Slop & Template Bawaan
- Menghapus teks "UjiCOBA.online", "Online TOEFL & TOEIC Prediction Test" generik, dan ilustrasi perkantoran korporat (`banner-right-image.png`).
- Mengganti ilustrasi dengan kartu visual akademik bertema islami modern, fokus pada kompetensi santri, keunggulan Ma'had Aly, dan sertifikasi e-TOEFL resmi.
- Memperbarui tag SEO pada `public/id/index.php` dan `resources/views/app.blade.php`:
  - `<title>`: `e-TOEFL Ma'had Aly Hasyim Asy'ari Tebuireng - Platform Uji Kompetensi Bahasa Inggris`
  - `<meta name="description">`: `Platform resmi Ujian e-TOEFL & Prediksi Kemampuan Bahasa Inggris Mahasantri Ma'had Aly Hasyim Asy'ari Pondok Pesantren Tebuireng Jombang.`
  - Open Graph tags (`og:title`, `og:description`, `og:image`, `og:url`).
  - Struktur hierarki heading `<h1>` dan `<h2>` yang bersih dan semantik.

---

## 6. CHECKLIST TAHAPAN KERJA & JAMINAN STABILITAS SISTEM

- [x] **Fase 1: Dokumen Acuan (Blueprint):** Pemetaan arsitektur dan pembuatan `PROJECT_BLUEPRINT.md`.
- [x] **Fase 2: Perbaikan Bug Lingkungan & Cloudflare:**
  - [x] Perbaikan error autoload pada `e-toefl.test` dan sinkronisasi ke `toefl`.
  - [x] Penanganan PHP 8.4 deprecations warning agar tidak merusak response header.
  - [x] Penyesuaian SSL TrustProxies & HTTPS enforcement untuk Cloudflare Tunnel.
- [x] **Fase 3: Migrasi Brevo Email API:**
  - [x] Pembuatan `BrevoEmailService` dengan dukungan REST API v3 dan fallback SMTP.
  - [x] Pengalihan seluruh notifikasi registrasi, aktivasi, reset password, reminder, dan invoice/token ke Brevo Email.
  - [x] Pengalihan notifikasi admin (`SendNewUserNotification`, `SendTransactionNotification`) ke email admin resmi.
  - [x] Pembaruan file konfigurasi `.env`, `.env.example`, dan `config/mail.php`.
- [x] **Fase 4: Pembaruan Kontak & Branding Ma'had Aly:**
  - [x] Pembaruan variabel admin (WA Username, Email Mahad Aly, Alamat Tebuireng Jombang).
  - [x] Pembaruan landing page `/id/index.php` dengan tema warna Tebuireng (`#0A3D24`, `#007A1E`, `#FFFFFF`, `#C69C2B`) bebas AI slop.
  - [x] Pembaruan meta tags SEO, favicon, dan logo e-TOEFL Ma'had Aly.
  - [x] Pembaruan footer dan komponen layout admin/user.
- [x] **Fase 5: Validasi & Pengujian Inti Ujian:**
  - [x] Verifikasi tidak ada regresi pada login, pengerjaan soal, pemutaran audio listening, dan perhitungan nilai.
  - [x] Verifikasi hasil build Vite dan aset statis.
