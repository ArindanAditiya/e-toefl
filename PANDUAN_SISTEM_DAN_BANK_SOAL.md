# PANDUAN PENGELOLAAN SISTEM & BANK SOAL e-TOEFL
## Ma'had Aly Hasyim Asy'ari Tebuireng Jombang
**Portal Resmi:** `https://etoefl.mahatbi.or.id` | **Local/Staging:** `http://toefl.test`

Dokumen ini merupakan panduan operasional komprehensif bagi Administrator, Penguji, dan Tim IT Ma'had Aly Hasyim Asy'ari Tebuireng dalam mengelola bank soal, mengunggah audio listening, memantau ujian, mengatasi peserta yang terblokir, hingga penerbitan sertifikat digital.

---

## DAFTAR ISI
1. [Tutorial Input Bank Soal & Audio Listening](#1-tutorial-input-bank-soal--audio-listening)
   - 1.1 Persyaratan & Format File Audio
   - 1.2 Struktur Soal (Pertanyaan vs Directions)
   - 1.3 Alur Input Soal per Section (Listening, Structure, Reading)
   - 1.4 Pengaturan Batas Putar Audio & Timer Pengerjaan
   - 1.5 Verifikasi File Audio di Server Storage
2. [Cara Kerja Sistem Menyeluruh](#2-cara-kerja-sistem-menyeluruh)
   - 2.1 Alur Autentikasi Pengguna (Email-Only & Google OAuth)
   - 2.2 Alur Transaksi & Aktivasi Paket Ujian (Gratis vs Berbayar)
   - 2.3 Sistem Keamanan Ujian (Anti-Cheat & Tab Switch Guard)
   - 2.4 Panduan Admin Membuka Blokir Peserta (Unblock Grade)
   - 2.5 Algoritma & Formula Konversi Nilai TOEFL / TOEIC
   - 2.6 Format & Penerbitan e-Sertifikat Digital Presisi 1 Halaman
3. [Troubleshooting & Pemeliharaan Sistem](#3-troubleshooting--pemeliharaan-sistem)

---

## 1. TUTORIAL INPUT BANK SOAL & AUDIO LISTENING

### 1.1 Persyaratan & Format File Audio
Bagian ujian **Listening Comprehension** mengandalkan pemutaran audio secara langsung di peramban (browser) peserta. Agar kompatibel dengan semua jenis perangkat (Desktop, Android, iOS/Safari), patuhi pedoman berikut:
- **Format File Wajib:** `.mp3` (MPEG Layer-3 Audio). Format lain seperti `.wav`, `.m4a`, atau `.ogg` tidak disarankan karena variasi dukungan browser mobile.
- **Bitrate Rekomendasi:** `128 kbps` (Stereo atau Mono). Bitrate ini memberikan keseimbangan optimal antara kejernihan suara narator dan ukuran file yang ringan.
- **Batas Ukuran File:** Maksimal **10 MB per file audio** (sesuai konfigurasi upload web server).
- **Penamaan File:** Gunakan huruf kecil, angka, dan tanda strip/garis bawah, misalnya:
  - `listening_part_a_01.mp3`
  - `toefl_sec1_conv05.mp3`
  *(Catatan: Sistem sudah dilengkapi fitur `encodeURI` otomatis, sehingga nama file yang mengandung spasi tetap dapat diputar, namun penggunaan nama tanpa spasi lebih direkomendasikan).*

### 1.2 Struktur Soal (Pertanyaan vs Directions)
Dalam sistem e-TOEFL Ma'had Aly, terdapat dua jenis item soal:
1. **Type 1 (Question / Soal Ujian):**
   - Merupakan butir soal pilihan ganda (A, B, C, D atau E).
   - Memiliki kunci jawaban, opsi jawaban teks/gambar, bobot nilai, serta pilihan input audio.
   - Ditampilkan dengan penomoran urut di panel navigasi peserta.
2. **Type 2 (Directions / Petunjuk Pengerjaan):**
   - Berisi teks instruksi atau petunjuk pengerjaan bagian tes (misalnya petunjuk *Part A: Short Dialogues*).
   - **Tidak memiliki nomor soal dan tidak memiliki kunci jawaban**.
   - Di panel navigasi peserta akan muncul sebagai penanda khusus tombol berwarna kuning bertuliskan **Directions**.

### 1.3 Alur Input Soal per Section
Ujian standar TOEFL ITP / Prediction terbagi menjadi 3 Section berurutan:
- **Section 1: Listening Comprehension (50 Soal)**
  - *Part A: Short Conversations* (Soal 1–30)
  - *Part B: Longer Conversations* (Soal 31–38)
  - *Part C: Talks / Lectures* (Soal 39–50)
  - Pada setiap butir soal Part A/B/C, aktifkan toggle **Input Audio: Aktif**, lalu unggah file `.mp3` rekaman percakapan/soal.
- **Section 2: Structure and Written Expression (40 Soal)**
  - *Structure* (Soal 1–15: Melengkapi kalimat rumpang)
  - *Written Expression* (Soal 16–40: Mengidentifikasi bagian kata yang salah/garis bawah A, B, C, D).
  - Bagian ini **tidak menggunakan audio**.
- **Section 3: Reading Comprehension (50 Soal)**
  - Terdiri dari 5 teks bacaan akademis, masing-masing diikuti 9–11 pertanyaan.
  - Masukkan teks bacaan pada kolom soal atau gunakan *Direction / Teks Pengantar* jika satu bacaan berlaku untuk beberapa soal.

### 1.4 Pengaturan Batas Putar Audio & Timer Pengerjaan
Pada menu pembuatan/pengeditan soal Listening di Admin:
- **Audio Play Limit (`audio_played_limit`):**
  - Nilai standar: `1` (audio hanya dapat diputar 1 kali sesuai standar ujian TOEFL resmi).
  - Jika diset ke `2`, peserta dapat memutar ulang audio sebanyak 2 kali.
- **Audio Answer Time (`audio_answer_time`):**
  - Waktu toleransi menjawab setelah audio selesai berputar (dalam satuan detik atau milidetik).
  - Jika diisi, countdown timer akan menghitung mundur setelah audio selesai, dan jika waktu habis sistem dapat secara otomatis mengalihkan peserta ke soal berikutnya.

### 1.5 Verifikasi File Audio di Server Storage
File audio yang diunggah disimpan di server pada jalur direktori:
```bash
# Jalur fisik server:
storage/app/public/upload_files/questions/

# Jalur akses publik (via symlink):
public/storage/upload_files/questions/
```
Jika file audio tidak terdengar di browser peserta, lakukan pengecekan berikut di terminal server:
1. Pastikan symlink storage telah terpasang dengan benar:
   ```bash
   php artisan storage:link
   ```
2. Pastikan izin akses folder (*permissions*) memungkinkan web server membaca file (chmod 755 / 775 pada Linux).

---

## 2. CARA KERJA SISTEM MENYELURUH

### 2.1 Alur Autentikasi Pengguna
Sistem e-TOEFL Ma'had Aly Hasyim Asy'ari menggunakan alur autentikasi modern yang praktis dan terlindungi:
1. **Registrasi Akun Baru (Email-Only):**
   - Calon peserta hanya perlu memasukkan **Nama Lengkap**, **Alamat Email**, dan **Password**.
   - Tidak ada ketergantungan nomor WhatsApp yang membingungkan peserta.
2. **Login Sekali Klik dengan Google (Google OAuth):**
   - Calon peserta dapat langsung menekan tombol **"Lanjutkan dengan Google"**.
   - Jika email Google belum terdaftar, sistem secara otomatis membuatkan akun baru dan langsung memasukkannya ke sistem.
3. **Perlindungan Cloudflare Turnstile:**
   - Halaman Login, Register, dan Lupa Password diproteksi oleh *Cloudflare Turnstile* cerdas tanpa CAPTCHA gambar rumit untuk mencegah serangan bot.
4. **Pemulihan Password (Forgot Password) Email-Only:**
   - Peserta cukup memasukkan email terdaftar.
   - Sistem menerbitkan password acak baru yang aman dan mengirimkannya ke inbox email peserta melalui layanan Brevo (dengan kontrol sakelar `BREVO_ACTIVE` di `.env`).

### 2.2 Alur Transaksi & Aktivasi Paket Ujian
1. **Paket Ujian Gratis (`Rp 0` / Ujian Trial):**
   - Sistem mendeteksi paket berharga `0`.
   - Transaksi langsung berstatus **Aktif / Selesai (Settled)** secara instan.
   - Peserta tidak perlu menunggu konfirmasi admin dan tidak ada bug pemotongan diskon minus. Tombol **"Mulai Ujian"** langsung tersedia di dashboard peserta.
2. **Paket Ujian Berbayar:**
   - Sistem menerbitkan nomor invoice unik dengan rincian transfer bank resmi Ma'had Aly Hasyim Asy'ari.
   - Dilengkapi tombol interaktif **"Konfirmasi via WhatsApp"** yang secara otomatis membuka chat ke nomor kontak admin resmi dengan pesan yang sudah terisi kode invoice dan nama peserta.
   - Nomor WhatsApp admin dapat diperbarui kapan saja melalui menu **Pengaturan Web** di dashboard Administrator tanpa perlu mengubah kode sumber.

### 2.3 Sistem Keamanan Ujian (Anti-Cheat & Tab Switch Guard)
Untuk menjamin integritas hasil tes, sistem dilengkapi modul pengawas ujian daring (*Online Proctoring*):
- **Deteksi Perpindahan Tab / Jendela Browser:**
  Sistem memantau event `visibilitychange` dan `blur` pada browser peserta.
- **Peredam False-Positive (Debounce 1000ms):**
  Untuk mencegah terblokirnya peserta akibat ketidaksengajaan (seperti refresh halaman, transisi antar bagian soal, atau popup browser), sistem menerapkan timer penundaan (*debounce*) selama 1 detik dan bendera navigasi internal (`isNavigating`).
- **Sistem Kuota Toleransi:**
  - Setiap kali peserta terbukti meninggalkan jendela ujian (berpindah tab atau membuka aplikasi lain), muncul peringatan resmi dan kuota toleransi berkurang (standar: 3 kali kesempatan).
  - Jika kuota habis, sesi ujian peserta otomatis terkunci (**Blocked**), nilai dibekukan ke `0`, dan peserta diarahkan untuk menghubungi pengawas/admin.

### 2.4 Panduan Admin Membuka Blokir Peserta (Unblock Grade)
Jika ada peserta yang terblokir akibat masalah teknis (misalnya gangguan browser), Administrator dapat membuka blokir dengan aman tanpa menghapus jawaban peserta:
1. Masuk ke halaman **Admin** -> Buka menu **Ujian** -> Pilih **Riwayat Ujian / Nilai Peserta** (`/exams/grades`).
2. Cari nama atau email peserta yang bersangkutan.
3. Pada baris data peserta dengan status diblokir, klik tombol aksi **Buka Blokir (Unblock)** (`/exams/grades/{id}/unblocked`).
4. Sistem akan secara otomatis:
   - Mengembalikan status `is_blocked = 0`.
   - Merestorasi kembali kuota toleransi peserta ke nilai awal.
   - Menghitung kembali nilai asli berdasarkan rekaman jawaban yang sudah diinput tanpa ada data yang hilang.
5. Peserta dapat memuat ulang (*refresh*) halaman ujian dan melanjutkan pengerjaan.

### 2.5 Algoritma & Formula Konversi Nilai TOEFL / TOEIC
Sistem menggunakan konversi standar internasional:
- **TOEFL Prediction (PBT / Paper-Based Scale):**
  - Menghitung jumlah jawaban benar (*Raw Score*) di masing-masing dari 3 Section.
  - Mengonversi *Raw Score* ke *Converted Score* menggunakan tabel konversi baku yang tersimpan di database (`conversions` table):
    - Section 1 (Listening): Skala 31 – 68
    - Section 2 (Structure): Skala 31 – 68
    - Section 3 (Reading): Skala 31 – 67
  - **Rumus Skor Akhir:**
    $$\text{Total Score} = \frac{(\text{Score S1} + \text{Score S2} + \text{Score S3}) \times 10}{3}$$
    Rentang skor akhir TOEFL adalah **310 hingga 677**.
- **TOEIC Prediction Scale:**
  - Listening Section: Skala 5 – 495
  - Reading Section: Skala 5 – 495
  - Rentang skor akhir TOEIC adalah **10 hingga 990**.

### 2.6 Format & Penerbitan e-Sertifikat Digital Presisi 1 Halaman
Setelah ujian selesai, peserta yang memenuhi kualifikasi dapat mengunduh sertifikat digital dalam format PDF:
- **Layout Presisi 1 Halaman (A4 Landscape):**
  Tata letak PDF dirancang dengan dimensi pasti `283mm x 196mm` sehingga hasil ekspor DomPDF dijamin **pasti 1 lembar utuh (Page 1 of 1)** tanpa lembar kosong kedua.
- **Identitas Lembaga Resmi:**
  - Kop Resmi: **MA'HAD ALY HASYIM ASY'ARI**, *Lembaga Bahasa - Pondok Pesantren Tebuireng Jombang*.
  - Alamat: *Jl. Irian Jaya No. 10, Tebuireng, Cukir, Diwek, Jombang, Jawa Timur 61471*.
  - Logo institusi beresolusi tinggi dengan watermark transparan di latar belakang.
- **Pejabat Penandatangan:**
  - Ditandatangani secara sah oleh: **Abdul Karim Amrulloh**
  - Jabatan: **Kepala Lembaga Bahasa**
  - Dilengkapi scan tanda tangan dan stempel resmi Ma'had Aly (`ttd.png`).
- **Verifikasi Digital (QR Code):**
  Setiap sertifikat memuat nomor seri unik dan kode QR verifikasi yang terhubung langsung ke portal resmi:
  `https://etoefl.mahatbi.or.id/user/grades/{id}/certificate`
  Pihak kampus, instansi, atau verifikator dapat memindai QR code tersebut untuk memvalidasi keaslian sertifikat secara real-time.

---

## 3. TROUBLESHOOTING & PEMELIHARAAN SISTEM

| Kendala / Masalah | Penyebab Umum | Langkah Solusi |
|---|---|---|
| **Audio listening tidak bersuara saat soal dibuka** | Kebijakan Autoplay pada browser modern (Chrome/Safari) memblokir audio otomatis tanpa interaksi pengguna. | Peserta cukup mengklik tombol putar manual (ikon Play) di samping pemutar audio yang telah disediakan sistem. |
| **Nilai akhir peserta tampil 0 setelah ujian selesai** | Peserta mengalami pemblokiran anti-cheat (`is_blocked = 1`) sebelum ujian selesai. | Buka menu Admin `/exams/grades`, cari nama peserta, lalu klik **Buka Blokir**. Sistem akan mengembalikan nilai hasil tes peserta secara otomatis. |
| **Tanda tangan/logo tidak muncul di file PDF sertifikat** | Path file gambar di DomPDF tidak dapat dijangkau oleh server lokal. | Sistem menggunakan path fisik lokal `public_path('assets/images/ttd.png')` dan logo base64/lokal sehingga aman dirender baik di Laragon lokal maupun VPS server produksi. |
| **Email reset password tidak terkirim ke peserta** | Kunci API Brevo belum diisi atau kuota kirim bulanan Brevo habis. | Pastikan `BREVO_ACTIVE=true` dan `BREVO_API_KEY` terisi di file `.env`. Jika Brevo nonaktif (`BREVO_ACTIVE=false`), sistem tetap aman tanpa melempar error 500. |

---
*Dokumen ini diterbitkan oleh Tim Pengembang e-TOEFL Ma'had Aly Hasyim Asy'ari Tebuireng.*
*Terakhir diperbarui: 23 November 2026.*
