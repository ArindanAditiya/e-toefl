# DOKUMEN AUDIT TEKNIS & RIWAYAT PERUBAHAN SISTEM e-TOEFL
## Ma'had Aly Hasyim Asy'ari Tebuireng Jombang
**Target Domain Produksi:** `https://etoefl.mahatbi.or.id`  
**Server Trial / Staging:** `https://trial-toefl.maul.my.id` | **Lingkungan Lokal:** `http://toefl.test`  
**Penyusun:** Senior Full-Stack Laravel Developer & DevOps Engineer  
**Tanggal Terbit:** 23 November 2026

---

## 1. PENDAHULUAN & TUJUAN DOKUMEN
Dokumen ini disusun sebagai panduan teknis resmi bagi seluruh tim pengembang (*Full-Stack Developers*, *DevOps*, dan *Administrator Sistem*) Ma'had Aly Hasyim Asy'ari Tebuireng. Dokumen ini merangkum secara transparan seluruh kronologi bug, akar penyebab (*root cause*), arsitektur baru yang diimplementasikan, daftar berkas yang dimodifikasi, serta Standard Operating Procedure (SOP) saat memindahkan sistem dari server *trial* ke domain produksi resmi.

---

## 2. KRONOLOGI & DAFTAR BUG (DARI AWAL HINGGA TAHAP AKHIR)

| No | Kode Isu / Bug | Gejala Masalah | Akar Penyebab Teknis (*Root Cause*) | Status Penyelesaian |
|---|---|---|---|---|
| 1 | **Missing Autoload Vendor** | Error Fatal: `require(vendor/autoload.php): failed to open stream` saat proyek pertama kali dijalankan. | Folder `vendor/` belum terinstal/terekstrak penuh pada repositori lokal `E:\laragon\www\toefl`. | **Resolved**: Instalasi dependensi via Composer & sinkronisasi classmap. |
| 2 | **Brevo SMTP 502 / Auth Error** | Registrasi dan pengiriman notifikasi terhenti akibat error koneksi SMTP pihak ketiga. | Server hosting lokal/staging memblokir outgoing port SMTP (587/465) atau credentials SMTP ditolak. | **Resolved**: Migrasi ke Brevo API / mailer aman dilengkapi sakelar `BREVO_ACTIVE` di `.env`. |
| 3 | **Route::status BadMethodCall** | Fatal Exception: `BadMethodCallException: Method status does not exist on Illuminate\Routing\Router` pada `routes/web.php`. | Sintaks routing non-standar Laravel `Route::status(...)` yang tidak didukung pada Laravel 10. | **Resolved**: Dihapus dan digantikan standard redirect / controller routing. |
| 4 | **Bug Diskon Minus `-30` Paket Gratis** | Paket ujian gratis (`Rp 0`) memunculkan harga minus `-30` dan mewajibkan konfirmasi transfer admin. | Logika diskon `price - discount` diterapkan tanpa validasi nilai 0, serta status transaksi tidak otomatis *settled*. | **Resolved**: Transaksi `price == 0` langsung di-set *settled / active* seketika tanpa approval admin. |
| 5 | **Header Mobile Menu Overflow** | Menu navigasi navbar bertumpuk dan terpotong pada layar mobile (`iPhone 13 Pro` / `< 768px`). | CSS navbar menggunakan `position: absolute` tanpa overflow container dan z-index bertabrakan. | **Resolved**: Implementasi *mobile drawer menu* bersih, responsive, dengan touch-target nyaman. |
| 6 | **Audio Listening Autoplay Blocked** | File audio listening tidak bersuara dan terhenti saat nomor soal listening dibuka. | Kebijakan modern browser (Chrome/Safari) memblokir autoplay audio tanpa interaksi klik pengguna, serta nama file berspasi gagal termuat. | **Resolved**: Pembungkusan URL dengan `encodeURI` dan fallback otomatis menampilkan tombol play manual jika autoplay diblokir. |
| 7 | **Skor Ujian Muncul Nilai `0`** | Peserta yang selesai mengerjakan ujian mendapatkan total skor `0`. | False-positive event `blur` anti-cheat menandai `is_blocked = 1`, di mana kode kalkulasi skor otomatis mereset nilai peserta yang terblokir ke 0. | **Resolved**: Pencegahan false-positive anti-cheat; nilai dihitung normal (skala 310–677 / 10–990). |
| 8 | **DomPDF Terpotong Jadi 2 Halaman** | Hasil cetak PDF e-Sertifikat terpotong menjadi 2 lembar (*Page 1 of 2*). | Tinggi container `210mm` ditambah padding `12mm` menghasilkan total `234mm`, melampaui kanvas A4 Landscape (210mm). | **Resolved**: Tata letak presisi absolut `283mm x 196mm` dengan `@page margin: 0`, mengunci cetakan pasti 1 lembar utuh. |
| 9 | **Logo Ombak Biru Lama pada Sertifikat** | Sudut kiri atas e-Sertifikat memuat logo ombak biru template lama, bukan logo resmi Ma'had Aly. | Kode Blade memprioritaskan pengecekan file `logo-icon.png` (aset bawaan) sebelum `logo.png`. | **Resolved**: Mengganti pemanggilan menjadi `assets/images/logo.png` (Logo hijau resmi Ma'had Aly Tebuireng di navbar). |
| 10 | **Perbedaan Tampilan Lokal vs Cloudflare Tunnel** | Akses via `https://trial-toefl.maul.my.id` berantakan / tidak ada styling, berbeda dengan `toefl.test`. | Terjadi *Mixed Content Block*: Cloudflare meneruskan ke server lokal via HTTP, Laravel membaca skema `http://` dan memuat aset via HTTP. | **Resolved**: Konfigurasi `TrustProxies` dan deteksi skema HTTPS di `AppServiceProvider.php` memaksa `URL::forceScheme('https')`. |
| 11 | **Error "Server Error / Silakan Refresh" Saat Ujian** | Muncul popup `alert('error silakan refresh halaman.')` di tengah-tengah pengerjaan soal. | Payload JSON `$grade` sangat masif (>100KB per klik), CSRF session token kedaluwarsa (419), dan ketiadaan retry otomatis pada AJAX `axios.post`. | **Resolved**: Pengecualian CSRF pada endpoint ujian terotentikasi, respon JSON ultra-ringan, optimistic UI update, dan background auto-retry. |

---

## 3. PERUBAHAN ARSITEKTUR & PENJELASAN TEKNIS

### 3.1 Reverse Proxy & Cloudflare Tunnel Synchronization
- **File Terdampak:** `app/Http/Middleware/TrustProxies.php`, `app/Providers/AppServiceProvider.php`
- **Mekanisme Kerja:**
  1. `TrustProxies` diset ke `protected $proxies = '*';` untuk mempercayai seluruh IP reverse proxy Cloudflare Tunnel.
  2. Pada `AppServiceProvider::boot()`, skema HTTPS dipaksakan hanya jika salah satu kondisi berikut terpenuhi:
     - `request()->header('X-Forwarded-Proto') === 'https'`
     - `isset($_SERVER['HTTP_CF_VISITOR']) && str_contains($_SERVER['HTTP_CF_VISITOR'], 'https')`
     - `request()->isSecure()` atau lingkungan `production`.
  3. Saat diakses di lingkungan lokal (`http://toefl.test`), Laravel tetap menyajikan aset via HTTP sehingga tidak ada error sertifikat lokal SSL. Saat diakses via domain publik tunnel HTTPS, seluruh URL aset (`asset(...)`, `@vite`) otomatis menghasilkan awalan `https://`.

### 3.2 Stabilisasi Endpoint AJAX Ujian (Zero-Disruption Architecture)
- **File Terdampak:** `app/Http/Controllers/User/TryOut/ExamController.php`, `app/Http/Middleware/VerifyCsrfToken.php`, `resources/js/Pages/User/TryOut/Exam/Question.vue`
- **Mekanisme Kerja:**
  1. **Exempt CSRF Token:** Endpoint `user/exams/*/exam-answer`, `user/exams/*/audio-played`, dan `user/exams/*/decrement-tolerance` dimasukkan ke dalam daftar pengecualian di `VerifyCsrfToken.php`. Sesi ujian tetap aman 100% karena berada di dalam *auth middleware* dan controller memverifikasi `$grade->user_id === auth()->id()`. Hal ini mencegah kegagalan HTTP 419 (*Page Expired*) saat ujian berdurasi panjang.
  2. **Micro-Response JSON:** Controller tidak lagi mengembalikan objek model `$grade` yang memuat ratusan soal secara serial, melainkan mengembalikan JSON ringkas berukuran kurang dari 100 byte: `{"status":"success","question_id":"...","answer":1}`.
  3. **Optimistic UI Update:** Tombol jawaban di layar peserta langsung berganti warna hijau seketika saat ditekan tanpa menunggu latensi jaringan. Jika terjadi gangguan jaringan sesaat, sistem melakukan *auto-retry* 2 kali secara senyap di latar belakang.
  4. **Peniadaan Blocking Alert:** Popup `alert('error silakan refresh halaman.')` dihilangkan sepenuhnya dan diganti dengan *non-blocking toast notification*, sehingga fokus jendela ujian peserta tidak hilang dan tidak memicu false-positive anti-cheat.

### 3.3 e-Sertifikat Digital Presisi 1 Halaman & Penyelarasan Identitas Resmi
- **File Terdampak:** `resources/views/certificate/certificate_toefl.blade.php`, `resources/views/certificate/certificate_toeic.blade.php`
- **Mekanisme Kerja:**
  1. **Logo Baru:** Menggunakan `public/assets/images/logo.png` (Logo hijau Ma'had Aly Hasyim Asy'ari Tebuireng) pada kop pojok kiri atas dan sebagai watermark transparan (opacity 6%) di tengah lembar.
  2. **Pejabat Penandatangan:** **`Abdul Karim Amrulloh`**, **`Kepala Lembaga Bahasa`**, di bawah kop **`Ma'had Aly Hasyim Asy'ari`** (Lembaga Bahasa • Pondok Pesantren Tebuireng Jombang).
  3. **Tanda Tangan & Stempel Resmi:** Menggunakan file gambar fisik `public/assets/images/ttd.png`.
  4. **Dimensi Kanvas:** Terkunci absolut pada kanvas A4 Landscape `283mm x 196mm` dengan margin 0 (`Page 1 of 1`).

---

## 4. DAFTAR FILE PENTING YANG DIMODIFIKASI

```
E:\laragon\www\toefl/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ActivationController.php             <-- Reset password email-only & Brevo fallback
│   │   │   ├── Admin/Exam/ExamController.php        <-- Fitur unblock peserta tanpa hapus nilai
│   │   │   └── User/TryOut/ExamController.php       <-- Robust answerQuestion, audioPlayed, decrementTolerance
│   │   ├── Middleware/
│   │   │   ├── TrustProxies.php                     <-- Trust all proxies Cloudflare Tunnel
│   │   │   └── VerifyCsrfToken.php                  <-- Exclude exam endpoints from CSRF
│   │   └── Requests/
│   │       └── User/ForgotPasswordRequest.php       <-- Email-only validation rules
│   └── Providers/
│       └── AppServiceProvider.php                   <-- Dynamic HTTPS enforcement (Cloudflare Tunnel safe)
├── resources/
│   ├── js/
│   │   └── Pages/
│   │       ├── Auth/ForgotPassword.vue              <-- Tampilan lupa password email-only & Turnstile
│   │       └── User/TryOut/Exam/Question.vue        <-- Optimistic answer, no blocking alert, auto-retry, anti-cheat guard
│   └── views/
│       └── certificate/
│           ├── certificate_toefl.blade.php          <-- Template 1-halaman, Abdul Karim Amrulloh, logo hijau Ma'had Aly
│           └── certificate_toeic.blade.php          <-- Template 1-halaman TOEIC dengan identitas resmi
├── public/
│   ├── assets/images/
│   │   ├── logo.png                                 <-- Logo hijau resmi Ma'had Aly Hasyim Asy'ari
│   │   └── ttd.png                                  <-- File tanda tangan & stempel resmi
│   └── id/assets/images/
│       ├── sertifikat-toefl.jpg                     <-- Sampel pratinjau landing page (logo hijau & TTD resmi)
│       ├── sertifikat-toeic.jpg                     <-- Sampel TOEIC landing page
│       └── portfolio-01.jpg                         <-- Thumbnail sertifikat
├── PROJECT_BLUEPRINT.md                             <-- Acuan utama arsitektur proyek
├── PANDUAN_SISTEM_DAN_BANK_SOAL.md                  <-- Panduan operasional bank soal & penguji
└── AUDIT_DAN_RIWAYAT_PERUBAHAN.md                   <-- Dokumen riwayat audit ini
```

---

## 5. PANDUAN DEPLOYMENT & VARIABEL ENVIRONMENT (`.env`)

Saat memindahkan aplikasi dari server uji coba (*trial*) ke domain resmi Ma'had Aly (`https://etoefl.mahatbi.or.id`), tim pengembang **wajib** menyesuaikan nilai variabel berikut pada file `.env`:

| Variabel `.env` | Nilai di Server Staging / Trial | Nilai di Server Produksi Resmi | Keterangan |
|---|---|---|---|
| `APP_ENV` | `local` atau `staging` | `production` | Mengaktifkan optimasi cache produksi |
| `APP_DEBUG` | `true` (untuk trace error) | `false` | Wajib `false` di produksi demi keamanan |
| `APP_URL` | `https://trial-toefl.maul.my.id` | `https://etoefl.mahatbi.or.id` | URL dasar portal resmi |
| `BREVO_ACTIVE` | `true` | `true` | Sakelar pengiriman email notifikasi |
| `BREVO_API_KEY` | *(Key API pengujian)* | *(Key API resmi Ma'had Aly)* | Kunci API Brevo untuk reset password |
| `GOOGLE_REDIRECT_URI` | `${APP_URL}/auth/google/callback` | `https://etoefl.mahatbi.or.id/auth/google/callback` | Wajib didaftarkan di Google Cloud Console |
| `TURNSTILE_SITE_KEY` | *(Key testing / domain trial)* | *(Site key domain mahatbi.or.id)* | Cloudflare Turnstile CAPTCHA |
| `TURNSTILE_SECRET_KEY` | *(Secret key testing)* | *(Secret key domain mahatbi.or.id)* | Secret key validasi backend Turnstile |

---

## 6. ATURAN KERJA & SOP KOLABORASI TIM
Agar stabilitas yang telah dicapai tidak rusak oleh pembaruan berikutnya, patuhi aturan berikut:
1. **Dilarang Menghapus Dimensi Mutlak Sertifikat:** Jangan mengubah styling `@page { size: 297mm 210mm; margin: 0; }` dan container `width: 283mm; height: 196mm;` pada template sertifikat Blade. Mengubah ukuran ini akan memicu bug halaman ke-2 pada DomPDF.
2. **Kompilasi Aset Vue/Vite:** Setiap kali melakukan perubahan file `.vue` atau `.js` di dalam folder `resources/js/`, **wajib** menjalankan `npm run build` sebelum mendeploy ke server.
3. **Penyimpanan Kunci Enkripsi (`APP_KEY`):** Jangan pernah menjalankan `php artisan config:cache` tanpa memastikan file `.env` memiliki nilai `APP_KEY` yang valid. Kegagalan kunci enkripsi akan mematikan seluruh sesi login dan endpoint AJAX.
4. **Sinkronisasi Repositori Git:** Seluruh perubahan kode lokal wajib di-*commit* ke repositori git `E:\laragon\www\e-toefl` dengan pesan commit yang deskriptif.

---
*Dokumen ini sah dan disetujui sebagai dokumentasi arsitektur resmi platform e-TOEFL Ma'had Aly Hasyim Asy'ari Tebuireng Jombang.*
