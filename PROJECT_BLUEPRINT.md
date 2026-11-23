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

---

## 7. CHANGELOG / SPRINT PENYEMPURNAAN TAHAP 2 (UI/UX & EMAIL-ONLY REGISTRATION)

### 7.1 Pemetaan Lokasi Aset Logo Baru & Favicon
- **File Logo Asli Pengguna:** `storage/app/public/upload_files/img/logo.png` (resolusi 4128x1024 px, rasio horizontal ~4:1 dengan lambang resmi Ma'had Aly di sisi kiri dan tipografi resmi di sisi kanan).
- **Distribusi File Logo Baru:**
  - `public/storage/upload_files/img/logo.png` (sinkronisasi dari storage ke public storage)
  - `public/assets/images/logo.png` (aset tema aplikasi Laravel/Inertia)
  - `public/id/assets/images/logo.png` (aset landing page publik)
- **Pembuatan & Penempatan Favicon Tab Browser:**
  - Dihasilkan file favicon persegi (crop lambang stempel resmi Ma'had Aly resolusi tinggi):
    - `public/favicon.ico`
    - `public/favicon.png`
    - `public/id/assets/images/favicon.png`
    - `public/assets/images/favicon-32x32.png`
  - Diinjeksi ke `<head>` pada `public/id/index.php`, `resources/views/app.blade.php`, dan semua template auth dengan cache buster `?v=2`.

### 7.2 Pemetaan Komponen Navbar, Spacing Menu, & Sticky Header
- **File Terdampak:**
  - `public/id/assets/css/templatemo-space-dynamic.css`: Menghapus rule `height: 80px!important` dan `line-height: 100px` yang memotong logo saat window di-scroll, serta menata ulang class `.header-area`, `.background-header`, dan `.main-nav`.
  - `public/id/index.php`: Penyesuaian container navbar, padding vertikal `14px 0`, jarak menu item `gap: 8px`, perataan vertikal fleksibel (`align-items: center`), serta proporsi logo `height: 52px; width: auto; max-width: 240px; object-fit: contain;`.

### 7.3 Eliminasi Sisa Aksen Warna Merah/Pink Template Lama
- **Akar Masalah:**
  - Warna `#fe3f40` (merah coral Space Dynamic) masih terpasang dengan `!important` di 19 baris file `templatemo-space-dynamic.css`, menyebabkan link aktif menu (seperti "Beranda") dan tombol tetap bernuansa merah.
  - Section *"Alur Pelaksanaan Ujian e-TOEFL"* (`.about-us`) memuat file background gelombang gradasi merah/pink (`about-bg.png`).
  - Elemen dekorasi `.main-banner:after` dan `:before` memuat file `baner-dec-left.png` dan `baner-dec-right.png` berwarna merah/oranye.
- **Tindakan Penyempurnaan:**
  - Mengganti seluruh nilai `#fe3f40`, `#ff3b2c`, `#ff4d61`, dan `#f7eff1` pada `templatemo-space-dynamic.css` menjadi palet resmi Tebuireng:
    - Hijau Utama: `#007A1E`
    - Hijau Tua Pesantren: `#0A3D24`
    - Aksen Emas: `#C69C2B`
    - Hijau Lembut: `#EBF5EE`
  - Menonaktifkan `background-image: url(../images/about-bg.png)` pada section `.about-us` dan menggantinya dengan latar belakang akademik bersih gradasi hijau lembut (`#F4FAF5` ke `#FFFFFF`).
  - Menonaktifkan pseudo-elemen dekorasi banner merah `:before` dan `:after`.

### 7.4 Penyederhanaan Alur Pendaftaran (Full Email-Only Registration)
- **File Terdampak:**
  - `database/migrations/2026_11_22_000000_make_phone_number_nullable_in_students_table.php`: Menjadikan kolom `phone_number` pada tabel `students` menjadi `NULLABLE`.
  - `app/Actions/Fortify/CreateNewUser.php`: Menghapus kewajiban input nomor WhatsApp pada validasi backend (`'phone_number' => 'nullable'`).
  - `resources/js/Pages/Auth/Register.vue`: Menghapus field input Nomor WhatsApp dari form registrasi peserta.
  - `resources/js/Pages/Auth/ForgotPassword.vue`: Mengizinkan reset password menggunakan Email akun peserta (bukan lagi nomor WA).
  - `app/Http/Requests/User/ForgotPasswordRequest.php` & `app/Http/Controllers/ActivationController.php`: Mendukung pencarian akun via Email untuk penerbitan password baru via Brevo.
  - `public/id/index.php`: Memperbarui teks panduan Langkah 1 Ujian menjadi pendaftaran mandiri via email aktif.

---

## 8. SPRINT PERBAIKAN TAHAP 3 (MOBILE MENU & BREVO AUTH FIX)

### 8.1 Dokumentasi Masalah Teknis
1. **Bug Mobile Header (iPhone 13 Pro / Layar < 768px):**
   - *Gejala:* Item menu navigasi (`Beranda`, `Alur Ujian`, `TOEFL`, `TOEIC`, `Sertifikat`, `Hubungi Kami`, `Masuk Peserta`) terbuka dan menumpuk secara vertikal di samping logo, menyebabkan tinggi header meregang hingga >200px dan menutupi judul hero `e-TOEFL Ma'had Aly Hasyim Asy'ari`.
   - *Penyebab:* Rule CSS `header.header-area .main-nav .nav { display: flex !important; }` diterapkan secara global tanpa pembatas `@media (min-width: 992px)`. Hal ini menimpa CSS responsif bawaan dan menonaktifkan mekanisme jQuery `slideToggle()` pada hamburger button `.menu-trigger`.
   - *Solusi:* Membatasi `display: flex !important` hanya pada viewport desktop (`@media (min-width: 992px)`). Pada viewport mobile (`@media (max-width: 991px)`), tinggi header dikunci ringkas (64px), logo di sebelah kiri (38px), hamburger menu button di sebelah kanan, menu `.nav` dilipat rapi dalam dropdown drawer (`display: none` / `mobile-open`) dengan background putih, shadow halus, dan padding proporsional. Padding atas hero banner pada mobile disesuaikan (`padding-top: 90px`) sehingga judul hero terlihat 100% utuh tanpa terpotong.

2. **Bug Registrasi (Brevo SMTP Auth 502):**
   - *Gejala:* `Symfony\Component\Mailer\Exception\TransportException: Expected response code "250" but got code "502", with message "502 5.7.0 Please authenticate first"` saat pendaftaran peserta baru di `CreateNewUser.php:106`.
   - *Penyebab:* 
     a. Di `CreateNewUser.php:106`, sistem memanggil `$admin->notify(new SendNewUserNotification($user))`. Notification class ini menggunakan channel default `['mail']` yang otomatis menghubungi SMTP transport Laravel (`smtp-relay.brevo.com:587`). Karena kredensial SMTP `MAIL_USERNAME` dan `MAIL_PASSWORD` di `.env` kosong, server Brevo menolak koneksi dengan pesan `502 5.7.0 Please authenticate first`.
     b. Fallback transport pada `BrevoEmailService::sendEmail` juga memanggil `Mail::html()` tanpa mengecek apakah kredensial SMTP tersedia, sehingga jika REST API key belum disetel, error yang sama terjadi.
   - *Solusi:*
     a. Mengubah `BrevoEmailService` agar menggunakan **Brevo Transactional Email REST API v3 (`https://api.brevo.com/v3/smtp/email`)** via Laravel `Http::withHeaders(['api-key' => ...])` secara langsung dan andal.
     b. Menambahkan pengecekan kredensial sebelum fallback ke SMTP Laravel. Jika kredensial SMTP kosong di lingkungan lokal, sistem beralih aman ke logging (`Log::warning`/`Log::info`) tanpa melempar exception fatal ke pengguna.
     c. Mengubah `SendNewUserNotification` dan `SendTransactionNotification` agar memanggil email service resmi dan membungkus `$admin->notify()` dalam blok `try-catch (\Throwable $e)` sehingga proses registrasi peserta selalu sukses dan akun berhasil dibuat.

---

## 9. SPRINT PENYEMPURNAAN LANDING PAGE & CLEAN URL (TAHAP 4)

### 9.1 Arsitektur Clean URL (Root `/` Tanpa `/id/index.php`)
- **Tujuan:** Pengunjung yang membuka `http://toefl.test/` maupun `https://etoefl.mahatbi.or.id/` disajikan landing page utama secara langsung pada URL bersih (`/`), tanpa ada redirect atau akhiran `/id/index.php` pada address bar.
- **Rencana Rute & Controller:**
  - Membuat controller `App\Http\Controllers\LandingController` yang menangani rute utama `Route::get('/', [LandingController::class, 'index'])->name('home');`.
  - Membuat view Blade `resources/views/landing.blade.php` sebagai single source of truth untuk tampilan beranda institusi.
  - Mempertahankan backward compatibility untuk tautan lama `/id/index.php` dengan redirect permanen (`301 Moved Permanently`) ke root `/`.
  - Seluruh aset statis CSS, JS, dan gambar menggunakan path absolut `/id/assets/...` atau fungsi pembantu `asset()` sehingga tidak bergantung pada tag `<base href="/id/">` yang merusak navigasi anchor link (`#`).

### 9.2 Arsitektur Header Navbar Fleksibel (Desktop & Mobile)
- **Struktur Baris Desktop (`>= 992px`):**
  - Layout 3 Kolom sejajar horizontal:
    - **Kiri:** Logo e-TOEFL Ma'had Aly Hasyim Asy'ari Tebuireng (`max-height: 48px`, proporsional).
    - **Tengah:** Menu navigasi (`Beranda`, `Alur Ujian`, `Simulasi Mini`, `Paket Ujian`, `Kalkulator Skor`, `Sertifikat`, `FAQ`, `Kontak`).
    - **Kanan:** Tombol aksi peserta (`Masuk Peserta` & `Daftar`) sejajar rapi vertikal-tengah (`align-items: center`).
- **Struktur Baris Mobile (`< 992px` / iPhone 13 Pro):**
  - Ketinggian navbar dikunci presisi `64px`, `position: fixed; top: 0; left: 0; right: 0; z-index: 99999;`.
  - Hanya ada dua elemen sejajar di baris utama:
    - **Kiri:** Logo Ma'had Aly (`height: 36px`).
    - **Kanan:** Tombol Hamburger Menu (`☰`, 40x40px).
  - Saat tombol hamburger diklik, menu muncul sebagai slide-down drawer di bawah header (tinggi `calc(100vh - 64px)`, scrollable, backdrop halus) tanpa merusak atau mendorong baris header ke bawah.
  - Padding atas hero banner pada mobile disesuaikan (`padding-top: 88px`) agar judul hero tidak terpotong.

### 9.3 Fitur Interaktif Edukatif & Pemadatan Spacing (Anti-AI Slop)
1. **Interactive Mini Sample Test Widget:**
   - 3 contoh soal standar TOEFL ITP (*Structure*, *Written Expression*, *Reading*) dengan opsi interaktif A/B/C/D.
   - Evaluasi instan: kartu jawaban benar berwarna hijau `#007A1E` dan kartu jawaban salah berwarna merah halus, disertai penjelasan kunci jawaban dan pembahasan kaidah tata bahasa Inggris.
2. **Kalkulator & Matriks Konversi Skor TOEFL ITP:**
   - Slider simulasi interaktif untuk 3 seksi ujian:
     - Section 1: Listening Comprehension (skor terkonversi 31 - 68)
     - Section 2: Structure & Written Expression (skor terkonversi 31 - 68)
     - Section 3: Reading Comprehension (skor terkonversi 31 - 67)
   - Tampilan live perhitungan skor total `((L + S + R) * 10) / 3` beserta indikator predikat standar kelulusan akademik Ma'had Aly Tebuireng.
3. **FAQ Accordion Interaktif:**
   - 5 pertanyaan seputar teknis ujian, perangkat ujian (laptop/smartphone), jadwal sesi, masa berlaku sertifikat, dan legalitas QR-code verifikasi.
   - Buka-tutup halus dengan interaksi ramah sentuhan.
4. **Tab Switcher Detail Paket & Lightbox Modal Sertifikat:**
   - Tab switcher antara paket *TOEFL Prediction* dan *TOEIC Prediction*.
   - Preview sertifikat ber-barcode yang dapat diklik untuk diperbesar dalam modal resolusi tinggi.
5. **Pemadatan Spacing:**
   - Menghilangkan ruang kosong berlebih (white-space) dengan margin vertikal antar section yang proporsional (`50px - 60px` di desktop, `35px - 45px` di mobile).

---

## 10. SPRINT PERBAIKAN ROUTING, ALIGNMENT IKON, CLOUDFLARE TURNSTILE & GOOGLE OAUTH (TAHAP 5)

### 10.1 Akar Masalah Error `BadMethodCallException: Method Route::status does not exist`
- **Penyebab Teknis:**
  Pada file `routes/web.php` baris 23-24, pemanggilan rute redirect dituliskan sebagai `Route::redirect('/id', '/')->status(301);`. Method `Route::redirect(...)` mengembalikan instance objek `Illuminate\Routing\Route`. Di dalam Laravel framework, class `Illuminate\Routing\Route` **tidak memiliki method `status()`**, melainkan menerima kode status HTTP sebagai parameter ketiga: `Route::redirect($source, $destination, $status = 302);`.
- **Solusi yang Diterapkan:**
  Mengganti baris pemanggilan rute menjadi method standar Laravel resmi:
  ```php
  Route::permanentRedirect('/id', '/');
  Route::permanentRedirect('/id/index.php', '/');
  ```
  atau `Route::redirect('/id', '/', 301);` yang secara native mengembalikan redirect permanen HTTP 301 tanpa error `BadMethodCallException`.

### 10.2 Presisi Tata Letak Logo & Ikon (CSS Flexbox Centering)
- **Akar Masalah:**
  Elemen wrapper ikon sebelumnya menggunakan `display: inline-block; line-height: 36px;` pada class `.step-badge`. Pada render browser dengan ukuran font tertentu, ikon FontAwesome (khususnya ikon WhatsApp `fa-whatsapp`) tampak melenceng beberapa piksel dari titik pusat lingkaran.
- **Solusi:**
  Mengubah seluruh wrapper ikon ke CSS Flexbox presisi:
  ```css
  display: inline-flex;
  align-items: center;
  justify-content: center;
  line-height: 0;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  ```
  Serta menambahkan floating WhatsApp helpdesk button di pojok kanan bawah halaman dengan CSS centering presisi.

### 10.3 Integrasi Cloudflare Turnstile (Anti-Bot & Anti-Spam)
- **Tujuan:** Melindungi endpoint autentikasi publik (`/login`, `/register`, `/forgot-password`) dari serangan brute-force, credential stuffing, dan registrasi bot spam tanpa mempersulit pengguna.
- **Frontend Widget:**
  Menggunakan script resmi Cloudflare Turnstile `<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>` dengan atribut `data-theme="light"` dan `:data-sitekey="$page.props.turnstile.site_key"`.
- **Backend Verification:**
  - Endpoint verifikasi: `POST https://challenges.cloudflare.com/turnstile/v0/siteverify`
  - Parameter: `secret`, `response` (`cf-turnstile-response`), dan `remoteip`.
  - Helper validator `validateTurnstile($token, $ip)` dengan fallback aman saat mode lokal (local development) maupun saat kunci Turnstile di `.env` masih kosong agar akses pengerjaan/pengujian tidak terkunci.

### 10.4 Integrasi Google OAuth (Laravel Socialite / 1-Click Login & Register)
- **Tujuan:** Mempermudah mahasantri dan peserta umum mendaftar dan masuk hanya dengan 1 klik akun Google tanpa mengisi form pendaftaran yang panjang.
- **Skema Database:**
  - Tabel `users`: penambahan kolom `google_id` (varchar(191), nullable, unique), `avatar` (text/varchar(255), nullable), serta memastikan kolom `password` dapat bernilai nullable saat registrasi via OAuth.
  - Tabel `students`: kolom pelengkap wilayah/alamat (`province_id`, `city_id`, `district_id`, `village_id`, `address`, `gender`) dibuat nullable agar proses registrasi instan dengan Google tidak terhambat oleh foreign key constraint MySQL.
- **Alur & Endpoint:**
  - `GET /auth/google`: Mengarahkan pengguna ke Google OAuth Consent Screen via `Socialite::driver('google')->redirect()`.
  - `GET /auth/google/callback`: Menerima callback token dari Google, memeriksa apakah `google_id` atau `email` sudah terdaftar. Jika sudah, tautkan `google_id` dan login langsung; jika belum, buat record `User` & `Student` baru berstatus aktif (`is_active = 1`, `email_verified_at = now()`) lalu login otomatis.
- **Variabel Konfigurasi (`.env`):**
  - `TURNSTILE_SITE_KEY` & `TURNSTILE_SECRET_KEY`
  - `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI`

---

## 11. SPRINT SINKRONISASI DATABASE, PEMULIHAN AKUN ADMIN, PERBAIKAN ASET SERTIFIKAT & PANDUAN TRIAL (TAHAP 6)

### 11.1 Akar Masalah & Solusi Gambar Sertifikat Pecah (*Broken Image*)
- **Penyebab Teknis:**
  Pada file `resources/views/landing.blade.php` baris 1336 dan 1513, tag `<img>` memanggil `{{ asset('id/assets/images/portfolio-01.jpg') }}`. Berdasarkan audit berkas fisik di `public/id/assets/images/`, berkas `portfolio-01.jpg` tidak pernah ada dalam aset bawaan template. Berkas yang sesungguhnya tersedia dan beresolusi tajam adalah `sertifikat-toefl.jpg` (121 KB) dan `sertifikat-toeic.jpg` (186 KB).
- **Solusi yang Diterapkan:**
  1. Mengubah pemanggilan gambar pada view `landing.blade.php` mengarah ke file nyata: `{{ asset('id/assets/images/sertifikat-toefl.jpg') }}`.
  2. Menyalin file `sertifikat-toefl.jpg` menjadi alias `portfolio-01.jpg` di `public/id/assets/images/` untuk menjamin kompatibilitas referensi lama.

### 11.2 Sinkronisasi Akun Login Administrator (*"These credentials do not match our records"*)
- **Penyebab Teknis:**
  Data bawaan di seeder awal menggunakan email `administrator@myenglish.com` dengan hash password lama dari tahun 2022 (`77422424`). Ketika dicoba login dengan email institusi resmi Ma'had Aly (`admin@mahatbi.or.id`) atau password berbeda, autentikasi gagal karena email belum terdaftar di tabel `users`.
- **Solusi yang Diterapkan:**
  1. Membuat dan mengeksekusi seeder sinkronisasi `AdminUserSyncSeeder.php` yang memperbarui akun admin utama `20b2a4122c614bb68e41b1a6f3f37780`:
     - **Email:** `admin@mahatbi.or.id`
     - **Password:** `AdminTebuireng#2026`
     - **Level:** `1` (Admin)
     - **Status:** `is_active = 1`
     - **Verifikasi:** `email_verified_at = now()`
  2. Menyediakan akun admin sekunder `administrator@myenglish.com` dengan password yang sama (`AdminTebuireng#2026`) sebagai cadangan.
  3. Mengimplementasikan `App\Http\Responses\LoginResponse` untuk mengarahkan akun `level == 1` langsung ke rute `admin.dashboard` dan akun mahasantri `level == 2` ke `user.dashboard`.

### 11.3 Konfigurasi Environment Dinamis untuk Trial Lokal & Staging
- Parameter `GOOGLE_REDIRECT_URI` dikonfigurasi dinamis menggunakan ekspresi `"${APP_URL}/auth/google/callback"`, sehingga secara otomatis menyesuaikan baik saat menggunakan `http://toefl.test` (Laragon lokal) maupun tunnel uji coba sementara (misal `https://trial-toefl.maul.my.id`).
- Kunci `TURNSTILE_SITE_KEY` dan `TURNSTILE_SECRET_KEY` terisi default menggunakan Testing Key Cloudflare (`1x00000000000000000000AA` / `1x00000000000000000000000000000000BB`) yang selalu lolos verifikasi saat uji coba lokal.

---

## 12. SPRINT FINAL AUDIT: SAKELAR BREVO, AKSES OTOMATIS UJIAN GRATIS (RP 0), WHATSAPP INVOICE DINAMIS & SERTIFIKAT MA'HAD ALY (TAHAP 7)

### 12.1 Akar Masalah Notifikasi Brevo & Sakelar Aman (`BREVO_ACTIVE=false`)
- **Penyebab Teknis Email Tidak Masuk Inbox:**
  Pada implementasi awal, pengiriman email via Brevo REST API v3 menggunakan alamat pengirim default `config('mail.from.address')` (`mahadaly@tebuireng.ac.id`). Sementara itu, domain pengirim resmi yang telah terdaftar dan terverifikasi secara valid di dashboard Brevo (dengan reputasi IP terverifikasi) adalah `admin@etoefl.mahatbi.or.id`. Ketidaksesuaian domain pengirim menyebabkan API Brevo menolak (*400 Bad Request / Sender not verified*). Karena pada lapisan service terdapat penanganan fallback log yang tetap mengembalikan nilai `true`, antarmuka pengguna memunculkan status sukses meskipun email tidak pernah terkirim ke kotak masuk peserta.
- **Solusi yang Diterapkan:**
  1. Menambahkan sakelar konfigurasi `BREVO_ACTIVE=env('BREVO_ACTIVE', false)` di `config/services.php` dan `.env` / `.env.example`.
  2. Menetapkan alamat pengirim default terverifikasi `BREVO_SENDER_EMAIL="admin@etoefl.mahatbi.or.id"` dan nama pengirim `BREVO_SENDER_NAME="e-TOEFL Ma'had Aly Hasyim Asy'ari Tebuireng"`.
  3. Pada `App\Services\Notification\BrevoEmailService`, jika `BREVO_ACTIVE` bernilai `false`, sistem langsung mencatat log pengiriman secara lokal (`[BREVO DEACTIVATED]`) dan mengembalikan `true` secara instan tanpa melakukan HTTP call ke internet, sehingga aplikasi bebas dari risiko blocking, hanging, atau error saat jaringan offline/lokal.

### 12.2 Solusi Bug Nominal Minus `-30` & Alur Pembelian Paket Ujian Gratis (`Rp 0`)
- **Penyebab Teknis Bug `-30`:**
  Pada database tabel `exams`, paket *Try Out TOEFL 1* tersimpan dengan nilai `price = -30`. Akibatnya, pada tampilan katalog soal (`Exam/Index.vue`), kondisi `exam.price === 0` bernilai false, sehingga paket tersebut memunculkan badge harga merah bertuliskan `Rp. -30` dan tombol "Beli Paket Try Out" (bukan "Kerjakan Soal"). Ketika dibeli, tagihan bernilai negatif dan menghasilkan invoice tertunda (*pending*) yang membingungkan peserta.
- **Solusi yang Diterapkan:**
  1. Memperbarui seluruh data ujian di tabel `exams` yang bernilai negatif menjadi `price = 0` via database query.
  2. Menambahkan accessor `getPriceAttribute` pada model `Exam` dan validasi `min:0` pada `ExamRequest` untuk menjamin nilai harga tidak pernah kurang dari 0 (`max(0, (int) $value)`).
  3. Menyempurnakan `TransactionService::createTransacationWithExamId`:
     - Untuk paket gratis (`$price === 0`), total tagihan dikunci mutlak ke `Rp 0`.
     - Status transaksi otomatis disetel ke `done` (lunas/selesai) dengan token voucher otomatis terbit dan berstatus terpakai/aktif (`voucher_used = 1, voucher_activated = 1`).
     - Hak akses member (`is_member = 1` dan `member_access`) langsung disematkan pada akun siswa tanpa menunggu konfirmasi manual admin.
  4. Menyempurnakan `TransactionController::buy`: jika paket yang dipilih gratis (`$price === 0`), peserta tidak perlu dicegah oleh transaksi pending lain dan langsung diarahkan ke halaman detail ujian untuk langsung memulai tes.
  5. Memperbarui middleware `IsMember`: peserta yang mengerjakan paket gratis otomatis diberikan dispensasi akses ke simulasi ujian.

### 12.3 Dinamisasi Nomor WhatsApp pada Invoice & Kontak Admin
- **Penyebab Teknis:**
  Nomor kontak WhatsApp pada komponen Vue (`Invoice.vue` dan `Show.vue`) sebelumnya menggunakan nilai statis pada mixin `app.js` (`@mahadalytbi`).
- **Solusi yang Diterapkan:**
  1. Menghubungkan mixin `resources/js/app.js` dengan data bersama Inertia `this.$page.props.app.admin_wa`, `admin_email`, dan `admin_address` yang bersumber langsung dari `.env` (`ADMIN_WA`).
  2. Menambahkan fungsi helper formatting nomor telepon internasional (`formatWaLink`) agar nomor seperti `08...` otomatis diubah ke tautan aktif `https://wa.me/628...`.
  3. Menambahkan tombol aksi konfirmasi langsung WhatsApp pada invoice agar mahasantri/peserta cukup mengklik satu tombol untuk terhubung dengan helpdesk resmi.

### 12.4 Redesain Sertifikat Digital Resmi Ma'had Aly Hasyim Asy'ari
- **Pembaruan Template PDF (`certificate_toefl.blade.php` & `certificate_toeic.blade.php`):**
  - Mengganti referensi legacy (*"Imam Syafii"*, *"Executive Director of e-TOEFL"*, *"http://localhost:8000"*) dengan identitas akademik resmi Ma'had Aly.
  - **Kop Institusi:** *Pusat Bahasa Ma'had Aly Hasyim Asy'ari Pondok Pesantren Tebuireng Jombang*.
  - **Penandatangan Resmi:** *Kepala Pusat Bahasa Ma'had Aly Hasyim Asy'ari Tebuireng*.
  - **Verifikasi Online:** *https://etoefl.mahatbi.or.id/user/grades/{id}/certificate* lengkap dengan identitas sertifikat digital.
  - **Estetika Visual:** Bingkai sertifikat modern berbasis CSS dengan perpaduan warna hijau islami (`#0A3D24`) dan aksen emas elegan (`#C5A059`), bebas dari ketergantungan latar belakang bitmap lawas berkulitas rendah.
- **Pembaruan Sampel Gambar Landing Page:**
  - Menghasilkan file pratinjau sertifikat resmi resolusi tinggi untuk `public/id/assets/images/sertifikat-toefl.jpg`, `sertifikat-toeic.jpg`, dan alias `portfolio-01.jpg` yang mencerminkan identitas Ma'had Aly Tebuireng.

---

## 13. SPRINT FINAL POLISH: E-SERTIFIKAT 1-HALAMAN PRESISI, EMAIL-ONLY PASSWORD RESET, STABILISASI ANTI-CHEAT & PANDUAN BANK SOAL (TAHAP 8)

### 13.1 Penyempurnaan Cetak e-Sertifikat 1 Halaman Penuh (Page 1 of 1)
- **Akar Masalah Halaman Terpotong Jadi 2 (`Page 1 / 2`):**
  Pada implementasi awal, CSS kontainer sertifikat menggunakan `width: 297mm; height: 210mm; padding: 12mm;` pada elemen `body`. Di dalam mesin render DomPDF, `box-sizing: border-box` pada elemen `body` seringkali tidak terhitung secara presisi sehingga total tinggi (`210mm + 24mm = 234mm`) melampaui batas kanvas A4 Landscape (595.28 pt / 210mm). Kelebihan tinggi ini memaksa DomPDF memecah halaman menjadi 2 (*page break*), di mana halaman 1 hanya memuat bingkai luar dan seluruh konten terdorong ke halaman 2.
- **Solusi yang Diterapkan:**
  1. Menetapkan ukuran kertas absolut `@page { size: 297mm 210mm; margin: 0; }`.
  2. Mengunci elemen `html, body` pada `width: 297mm; height: 210mm; margin: 0; padding: 0; overflow: hidden;`.
  3. Menggunakan kontainer bersarang dengan koordinat absolut yang terkalibrasi (`width: 283mm; height: 196mm; position: absolute; top: 7mm; left: 7mm;`), sehingga seluruh elemen teks, tabel nilai, watermark, dan tanda tangan pas 100% dalam batas kanvas satu halaman utuh (*Page 1 of 1*).
  4. **Pembaruan Identitas Resmi:**
     - Mengganti penandatangan menjadi identitas resmi: **Abdul Karim Amrulloh** selaku **Kepala Lembaga Bahasa Ma'had Aly Hasyim Asy'ari Tebuireng**.
     - Memasang aset tanda tangan dan stempel resmi dari `public/assets/images/ttd.png` secara proporsional.
     - Memperbesar logo Ma'had Aly pada kop header serta menambahkan ornamen sudut (*corner ornaments*) hijau tua (`#0A3D24`) dan emas (`#C5A059`), serta *watermark* lambang Ma'had Aly transparan lembut di tengah latar belakang sertifikat.

### 13.2 Migrasi Reset Password Menjadi Full Email-Only
- **Penyebab & Kebutuhan:**
  Form lupa password sebelumnya masih memiliki dependensi ganda pada nomor WhatsApp (`required_without:phone_number`), yang membingungkan alur dan rawan gagal jika nomor WhatsApp belum tersinkronisasi.
- **Solusi yang Diterapkan:**
  1. Mengubah `ForgotPasswordRequest.php` menjadi strictly `'email' => 'required|email|exists:users,email'`.
  2. Menyederhanakan `ActivationController::storeForgotPassword` agar 100% memproses berdasarkan alamat email terdaftar, meng-generate kata sandi acak baru, dan mengirimkannya via template email resmi Ma'had Aly.
  3. Memperbarui tampilan `ForgotPassword.vue`: menghapus input nomor WhatsApp dan menyelaraskan panduan khusus email.

### 13.3 Stabilisasi Anti-Cheat (Blokir Pindah Tab) & Penanganan Audio / Skor
- **Akar Masalah False-Positive Blokir Tab:**
  Event listener `window.addEventListener("blur", confirm)` sebelumnya terpasang secara mentah tanpa mendeteksi konteks. Akibatnya, saat peserta berpindah bagian (*section*), melakukan *refresh* yang sah, atau saat browser memunculkan dialog izin media (*audio autoplay permission*), event `blur` terpanggil dan mengurangi batas toleransi secara salah, bahkan langsung memblokir ujian dan membuat nilai akhir menjadi `0`.
- **Solusi yang Diterapkan:**
  1. Menambahkan flag `isNavigating` pada komponen `Question.vue`. Saat peserta berpindah halaman, berpindah seksi, atau mengakhiri ujian secara sah, event listener dinonaktifkan sementara.
  2. Menggunakan kombinasi `document.visibilitychange` dan `window.blur` dengan debounce serta verifikasi `document.visibilityState === 'hidden'` agar hanya aktivitas keluar jendela yang sesungguhnya yang dihitung sebagai pelanggaran.
  3. Menambahkan pembersihan event listener pada lifecycle `onBeforeUnmount` / `onUnmounted`.
  4. Memperbaiki tombol putar audio manual (`playButtonAudio`) jika kebijakan *autoplay* browser memblokir audio otomatis, serta memastikan nama file audio di-encode secara aman (`encodeURI`).
  5. Memperbaiki fitur admin `unblocked` pada `ExamController.php` agar mengembalikan toleransi peserta dan membuka blokir (`is_blocked = 0`) tanpa menghapus riwayat pengerjaan.

### 13.4 Panduan Sistem dan Bank Soal (`PANDUAN_SISTEM_DAN_BANK_SOAL.md`)
- Menyusun panduan komprehensif untuk pengelola/admin mencakup tutorial input soal & audio listening bebas error, panduan alur pendaftaran, sistem paket soal, anti-cheat & pembukaan blokir peserta, serta penerbitan e-sertifikat ber-QR Code.

---

## 14. SPRINT STABILISASI UJIAN, SINKRONISASI TUNNEL/HTTPS, & PEMBARUAN LOGO SERTIFIKAT (TAHAP 9)

### 14.1 Penggantian Total Logo Lama pada e-Sertifikat dengan Logo Resmi Ma'had Aly
- **Akar Masalah:**
  Pada implementasi e-Sertifikat sebelumnya, kode Blade memeriksa keberadaan file gambar dengan prioritas salah: `@if(file_exists(public_path('assets/images/logo-icon.png')))` yang ternyata merupakan ikon ombak biru bawaan template lama, sebelum memeriksa logo hijau resmi Ma'had Aly (`logo.png`). Hal yang sama terjadi pada watermark latar belakang.
- **Solusi yang Diterapkan:**
  1. Memperbarui `resources/views/certificate/certificate_toefl.blade.php` dan `resources/views/certificate/certificate_toeic.blade.php`: memprioritaskan secara ketat `public_path('assets/images/logo.png')` (file logo lingkaran hijau Ma'had Aly Hasyim Asy'ari Tebuireng yang sama dengan navbar utama).
  2. Menerapkan logo hijau Ma'had Aly yang sama sebagai *watermark* transparan (opacity 6%) di tengah lembar sertifikat agar serasi dan bernuansa akademis pesantren.
  3. Memperbarui seluruh sampel render HTML dan gambar landing page (`sertifikat-toefl.jpg`, `sertifikat-toeic.jpg`, `portfolio-01.jpg`) sehingga sinkron dengan logo resmi terbaru.

### 14.2 Sinkronisasi Reverse Proxy Cloudflare Tunnel & Eliminasi Mixed Content
- **Akar Masalah:**
  Ketika aplikasi diakses melalui Cloudflare Tunnel (`https://trial-toefl.maul.my.id`), permintaan diteruskan ke web server lokal melalui protokol HTTP. Jika Laravel tidak mengonfigurasi header reverse proxy (`TrustProxies`) dengan benar, helper `asset()` dan `url()` menganggap skema koneksi adalah `http://`, sehingga memuat aset CSS/JS via HTTP dan memicu *Mixed Content Block* di browser (tampilan berantakan, styling rusak).
- **Solusi yang Diterapkan:**
  1. Konfigurasi `App\Http\Middleware\TrustProxies`:
     Mengatur `protected $proxies = '*';` dan menyertakan header `Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_HOST | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO | Request::HEADER_X_FORWARDED_AWS_ELB;`.
  2. Logika Deteksi Dinamis di `App\Providers\AppServiceProvider.php`:
     Menambahkan deteksi otomatis skema HTTPS dari header Cloudflare (`HTTP_X_FORWARDED_PROTO == 'https'` atau `HTTP_CF_VISITOR` mengandung `https` atau `request()->isSecure()`) untuk menjalankan `URL::forceScheme('https')`. Dengan demikian, saat diakses lewat Tunnel publik seluruh aset dimuat aman via HTTPS, sementara saat diakses di jaringan lokal (`http://toefl.test`) tetap dimuat via HTTP tanpa error sertifikat SSL lokal.

### 14.3 Penanganan Bug "Server Error / Silakan Refresh" Saat Pengerjaan Ujian
- **Akar Masalah:**
  1. *Ekskusi Endpoint Berulang & Payload Masif:* Pada `answerQuestion` dan `audioPlayed` di `ExamController.php`, setiap kali tombol opsi atau audio diklik, controller mengembalikan seluruh objek `$grade` yang memuat ratusan butir soal berseri JSON besar. Hal ini membebani bandwidth dan memicu latency/timeout pada koneksi seluler atau tunnel.
  2. *Kurangnya Defensive Validation:* Jika query pencarian index soal (`array_search`) menghasilkan `false`, PHP mencoba menulis ke array dengan index yang tidak valid, memicu HTTP 500.
  3. *CSRF Token Mismatch (HTTP 419):* Pada durasi ujian panjang (45–120 menit) atau ketika terjadi fluktuasi jaringan, CSRF token session dapat kedaluwarsa, menyebabkan request AJAX `axios.post` ditolak dengan status 419 yang memicu `alert('error silakan refresh halaman.')`.
  4. *UI Terhambat:* Pembaruan jawaban di UI sebelumnya menunggu respons jaringan selesai (*pessimistic update*), membuat peserta mengklik tombol berulang kali saat koneksi lambat.
- **Solusi yang Diterapkan:**
  1. Mengecualikan endpoint simpan jawaban dan audio dari verifikasi CSRF di `VerifyCsrfToken.php` (`user/exams/*/exam-answer`, `user/exams/*/audio-played`, `user/exams/*/decrement-tolerance`), dengan pengamanan ganda di controller memastikan kepemilikan sesi (`$grade->user_id === auth()->id()`).
  2. Mengoptimalkan controller agar mengembalikan respons JSON ultra-ringan (`status: success`, under 100 bytes) dan menerapkan blok `try-catch` dengan pencarian ID soal bertipe aman.
  3. Menerapkan *Optimistic UI Update* di `Question.vue`: warna tombol jawaban langsung aktif saat diklik, dilengkapi *auto-retry* 2 kali di latar belakang jika ada gangguan jaringan sementara, dan mengganti popup `alert()` pemblokir dengan *toast notification* non-intrusif.

---

## 15. SPRINT FINAL RELEASE: FIX BACKEND TURNSTILE VERIFICATION, SECURITY AUDIT, & GITHUB DEPLOYMENT (TAHAP 10)

### 15.1 Investigasi & Penyelesaian Bug Verifikasi Backend Cloudflare Turnstile
- **Gejala Masalah:**
  Pada formulir Login, Registrasi, dan Lupa Password, widget Cloudflare Turnstile berhasil menyelesaikan tantangan (centang hijau bertuliskan *"Berhasil!"*), namun saat formulir disubmit ke server, sistem menampilkan pesan error:
  `"Verifikasi keamanan Cloudflare Turnstile gagal. Silakan coba lagi."`.
- **Akar Masalah (Root Cause):**
  1. *Laragon / Windows cURL SSL Certificate (Error 60):*
     Secara default di lingkungan Windows/Laragon, PHP cURL tidak memiliki bundle sertifikat root CA Mozilla terbaru, sehingga pemanggilan HTTPS `Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', ...)` mengalami kegagalan handshake SSL cURL code 60 (`unable to get local issuer certificate`).
  2. *Rejection Akibat Parameter `remoteip` di Balik Reverse Proxy / Tunnel:*
     Ketika aplikasi diakses melalui Cloudflare Tunnel atau reverse proxy lokal, `request()->ip()` menghasilkan loopback/private IP (`127.0.0.1` atau `192.168.x.x`). Mengirimkan IP loopback/private ke Cloudflare `siteverify` menyebabkan Cloudflare menolak verifikasi dengan kode `invalid-remoteip` karena IP pemecah tantangan sebenarnya adalah IP publik pengguna. Dokumentasi resmi Cloudflare Turnstile menyatakan bahwa parameter `remoteip` bersifat opsional dan sebaiknya diabaikan jika aplikasi berjalan di balik proxy/tunnel yang tidak meneruskan IP publik tepercaya.
  3. *Whitespace pada Kunci Secret:*
     Adanya potensi spasi tersembunyi (*trailing spaces*) pada string kunci secret di `.env`.
  4. *Lifecycle SPA Inertia.js:*
     Inertia me-render halaman secara SPA dinamis tanpa full page reload. Skrip eksternal Turnstile `api.js` sering kali tidak otomatis me-render ulang elemen `#turnstile-container` yang baru dimasukkan ke DOM.
- **Solusi yang Diterapkan:**
  1. **Overhaul Helper `validateTurnstile()` di `app/Helpers/Helpers.php`:**
     - Menambahkan opsi HTTP Client Laravel `->withoutVerifying()` dan `->timeout(10)` agar kebal terhadap kendala sertifikat lokal di server pengujian/Windows cURL.
     - Menyaring parameter `remoteip`: memastikan IP lokal/loopback (`127.0.0.1`, `::1`, rentang RFC1918) tidak dikirimkan ke Cloudflare API.
     - Membersihkan secret key dengan `trim(config('services.turnstile.secret_key', ...))`.
     - Menambahkan penanganan whitelist untuk Turnstile Testing Keys resmi Cloudflare (`1x00000000000000000000000000000000BB`, dummy keys).
     - Menambahkan logging komprehensif saat verifikasi ditolak agar error kode spesifik dari Cloudflare dapat dipantau di `storage/logs/laravel.log`.
  2. **Inisialisasi Eksplisit di Komponen Vue (`Login.vue`, `Register.vue`, `ForgotPassword.vue`):**
     - Memanfaatkan lifecycle hook `onMounted()` untuk mendeteksi `window.turnstile` dan memanggil `window.turnstile.render('#turnstile-container', { callback: ... })`.
     - Menyimpan token hasil tantangan ke dalam *reactive state* Vue `turnstileToken` dan menyertakannya secara otomatis dalam payload `useForm()`.
     - Menyediakan elemen `<input type="hidden" name="cf-turnstile-response" :value="turnstileToken">` sebagai fallback kompatibilitas form POST murni.

### 15.2 Audit Menyeluruh 3 Tahapan Rilis
1. **Tahap Pra-Development / Setup:**
   - Kompatibilitas runtime: PHP 8.1 - 8.3 dengan ekstensi `curl`, `mbstring`, `openssl`, `pdo_mysql`, `gd`, `zip`.
   - Symlink penyimpanan public: `php artisan storage:link` terpasang mengarah ke `storage/app/public`.
   - Database migrations & seeders tersinkronisasi penuh dengan tabel user, role, permissions, kategori, soal, bank soal, dan paket ujian.
2. **Tahap Development / Trial Server (`http://toefl.test` & `https://trial-toefl.maul.my.id`):**
   - Autentikasi email dan Google OAuth teruji.
   - Cloudflare Turnstile terintegrasi dan lolos verifikasi backend.
   - Pembelian paket ujian gratis (Rp 0) terverifikasi langsung aktif tanpa perizinan manual admin.
   - Mekanisme anti-cheat toleransi tab refresh tidak lagi memicu false-positive blokir.
   - Layout PDF DomPDF e-Sertifikat teruji pas 1 halaman landscape A4 dengan tanda tangan Mudir *Abdul Karim Amrulloh* dan stempel resmi hijau Ma'had Aly.
3. **Tahap Production / Publikasi Resmi (`https://etoefl.mahatbi.or.id`):**
   - Konfigurasi `APP_ENV=production` dan `APP_DEBUG=false`.
   - Pengaktifan notifikasi transaksional email Brevo API (`BREVO_ACTIVE=true`).
   - Reverse proxy Cloudflare terkonfigurasi dengan `TrustProxies` dan SSL scheme auto-detection.

### 15.3 Keamanan Lingkungan & Sanitasi Kredensial (.gitignore & .env.example)
- Mengamankan `.gitignore` agar memblokir seluruh varian `.env` (`.env`, `.env.backup`, `.env.production`, `.env.*.local`), kunci sertifikat (`*.pem`, `*.key`), database dump (`*.sql`, `*.sqlite`), log, serta cache framework.
- Memperbarui file `.env.example` dengan seluruh kunci konfigurasi terbaru yang dibutuhkan aplikasi (Google OAuth, Brevo, Turnstile, Fonnte, Midtrans) menggunakan nilai placeholder yang aman tanpa mengekspos kredensial rahasia apapun ke repository Git.

### 15.4 Sinkronisasi Repository Git & Deployment GitHub
- Repositori Git resmi: `https://github.com/ArindanAditiya/e-toefl.git` pada branch `main`.
- Menyinkronkan seluruh codebase yang telah diaudit, teruji, dan bebas error dari direktori kerja `toefl` ke repositori `e-toefl`.
- Menyusun dokumentasi rilis komprehensif pada `README.md`, `PANDUAN_SISTEM_DAN_BANK_SOAL.md`, dan `AUDIT_DAN_RIWAYAT_PERUBAHAN.md`.







