# Analisis Alur Bisnis dan Panduan Pengguna e-TOEFL

Dokumen ini merangkum alur dan perilaku aplikasi berdasarkan implementasi saat ini. Aplikasi menyediakan kategori tryout, pembelian akses ujian, aktivasi voucher, pengerjaan ujian, penilaian, serta halaman administrasi.

## 1. Ringkasan aplikasi

e-TOEFL adalah aplikasi latihan/tryout TOEFL dan TOEIC berbasis web. Ada dua peran utama:

- **Pengguna/siswa**: mendaftar, mengaktifkan akun, memilih ujian, membeli atau mengaktifkan voucher, mengerjakan ujian, lalu melihat hasil.
- **Admin**: menyiapkan kategori dan bank soal, menerbitkan ujian, mengelola pembayaran dan voucher, serta mengelola akun dan informasi.

Secara teknis, aplikasi menggunakan Laravel untuk server dan aturan bisnis, Vue 3 + Inertia untuk antarmuka, serta Fortify untuk autentikasi/registrasi. Notifikasi aktivasi, pembayaran, dan voucher dikirim melalui WhatsApp.

## 2. Peta alur bisnis

### A. Persiapan ujian oleh admin

1. Buat **Kategori** (misalnya TOEFL atau TOEIC).
2. Buat **Kategori Penilaian** dan rentang konversi nilai jika diperlukan. Kategori ini biasanya mewakili bagian ujian/section.
3. Buat **Bank Soal/Judul Soal** untuk kategori tersebut. Tentukan jumlah pilihan dan section, serta pengaturan koreksi dan penilaian.
4. Tambahkan soal pada bank soal: isi section, arahan/teks, pilihan, kunci jawaban, dan audio jika soal listening.
5. Buat **Ujian**: pilih kategori dan bank soal, tentukan judul, deskripsi/instruksi, harga, durasi, pengacakan, navigasi, dan toleransi meninggalkan halaman.
6. Tambahkan rekening bank yang aktif untuk ditampilkan pada faktur pembayaran.
7. (Opsional) Kelola pengumuman dan FAQ yang akan dibaca pengguna.

Urutan ini penting karena ujian mengambil soal dari bank soal, dan pilihan durasi per section mengambil daftar kategori penilaian.

### B. Siklus pengguna dan pembelian

1. Pengguna mendaftar menggunakan nama, email, password, nomor WhatsApp aktif, alamat, gender, dan data wilayah.
2. Akun dibuat sebagai pengguna biasa dan belum berstatus member. Tautan aktivasi akun dikirim ke WhatsApp.
3. Pengguna membuka tautan aktivasi, lalu masuk dengan email dan password.
4. Dari menu **Ujian**, pilih kategori dan ujian.
5. Jika berbayar, buat transaksi. Sistem membuat faktur dengan tenggat pembayaran dua hari dan menampilkan rekening bank serta cara konfirmasi melalui WhatsApp.
6. Admin memeriksa bukti pembayaran dan memperbarui status transaksi:
   - `pending`: menunggu pembayaran;
   - `paid`: pembayaran telah diterima, menunggu penyelesaian/penerbitan token;
   - `done`: transaksi selesai; sistem membuat token voucher dan mengirimkannya melalui WhatsApp;
   - `failed`: transaksi dibatalkan.
7. Pengguna memasukkan token pada menu **Aktivasi Voucher**. Token hanya dapat diaktifkan sekali dan harus merupakan token milik transaksi pengguna tersebut.
8. Setelah aktivasi, akses ujian terkait dapat digunakan. Pengguna mulai ujian dari halaman detail ujian.
9. Jawaban tersimpan saat dipilih. Pengguna menuntaskan section terakhir, lalu hasil masuk ke riwayat ujian.

**Ujian gratis:** backend akan langsung menyelesaikan transaksi dan membuat token jika alur pembuatan transaksi dipanggil. Namun, katalog pengguna menampilkan tombol **Kerjakan Soal** untuk ujian gratis, bukan tombol pembelian; pengguna baru/non-member dapat terhenti oleh pemeriksaan status member saat mulai. Jika itu terjadi, hubungi admin untuk mengatur akses atau mengirimkan token melalui proses yang tersedia. Pengguna yang sudah berstatus member dapat mulai ujian gratis tanpa transaksi berbayar.

## 3. Panduan pengguna/siswa

### 3.1 Mendaftar dan mengaktifkan akun

1. Buka halaman **Registrasi**.
2. Isi semua kolom, termasuk nomor WhatsApp yang dapat menerima pesan, alamat lengkap, dan password minimal delapan karakter.
3. Kirim formulir dan periksa WhatsApp untuk tautan aktivasi akun.
4. Buka tautan aktivasi, lalu login.

Akun yang belum aktif akan dikeluarkan dari sesi login dan perlu diaktifkan terlebih dahulu. Jika tautan tidak diterima, hubungi admin.

### 3.2 Memilih dan membeli ujian

1. Login, lalu buka **Ujian**.
2. Pilih kategori, misalnya TOEFL atau TOEIC.
3. Pilih ujian yang diinginkan. Label **Gratis** menandakan harga nol; ujian berbayar menampilkan harga.
4. Tekan **Kerjakan Soal** untuk membuka detail ujian gratis/yang sudah tersedia, atau **Beli Paket Try Out** untuk ujian berbayar. Pengguna baru/non-member mungkin perlu meminta admin mengatur akses untuk ujian gratis.
5. Untuk pembelian, buka detail transaksi/faktur. Periksa nomor faktur, jumlah, tenggat, dan rekening tujuan.
6. Transfer sesuai jumlah, kemudian konfirmasi ke WhatsApp yang tercantum dengan nomor faktur dan bukti transfer.
7. Pantau status melalui **Riwayat Transaksi**. Setelah admin menyelesaikan transaksi, token dikirim melalui WhatsApp.

Satu transaksi berstatus pending dapat menghalangi pembelian ujian lain. Selesaikan transaksi itu atau hubungi admin sebelum membuat pembelian baru.

### 3.3 Mengaktifkan voucher

1. Jika admin telah memberikan token, buka **Aktivasi Voucher**.
2. Salin-tempel token dari pesan WhatsApp ke kolom token; hindari mengetik ulang agar tidak salah.
3. Tekan **Aktivasi**, lalu konfirmasi.
4. Pastikan pesan berhasil muncul. Riwayat aktivasi dan tautan ke ujian tersedia di halaman yang sama.

Token hanya dapat digunakan sekali dan hanya pada akun penerima transaksi.

### 3.4 Mengerjakan ujian

1. Dari kategori, buka detail ujian dan baca deskripsinya.
2. Tekan **Mulai Kerjakan**. Jika ujian pernah dimulai tetapi belum selesai, gunakan **Lanjut Mengerjakan**.
3. Periksa penghitung waktu dan instruksi sebelum menjawab. Untuk soal listening, ikuti batas pemutaran audio yang ditampilkan.
4. Pilih jawaban untuk menyimpannya. Gunakan navigasi nomor soal bila tersedia; jawaban yang telah dipilih ditandai berbeda.
5. Gunakan **Next/Prev** jika ditampilkan. Pada ujian ber-section, lanjutkan ke section berikutnya ketika selesai.
6. Pada section terakhir, tekan **Akhiri Ujian** dan konfirmasi. Jika waktu habis, ikuti dialog untuk mengakhiri ujian.

**Penting:** jangan berpindah tab/aplikasi atau meninggalkan halaman ujian. Aplikasi menampilkan peringatan dan mengurangi toleransi setiap kali halaman kehilangan fokus. Jika toleransi habis, ujian dapat diblokir dan harus ditangani admin. Timer ujian tetap berjalan berdasarkan waktu mulai/akhir yang tersimpan.

### 3.5 Melihat hasil dan sertifikat

1. Buka **Riwayat Ujian** untuk melihat ujian yang telah selesai.
2. Buka detail hasil untuk melihat nilai, rincian nilai per kategori (jika disediakan), peringkat, dan koreksi jawaban.
3. Koreksi jawaban hanya tampil jika pengaturan ujian mengizinkannya dan nilai memenuhi nilai minimum yang ditetapkan.
4. Tombol **Cetak Sertifikat** tersedia pada halaman detail hasil; template yang disediakan aplikasi adalah untuk kategori TOEFL dan TOEIC. Sertifikat tidak dikirim otomatis: peserta membukanya sendiri dari tombol tersebut, lalu dapat mengunduh atau mencetak PDF dari browser.

### 3.6 Profil, pengumuman, FAQ, dan lupa password

- **Profil** menampilkan data akun dan status member; gunakan halaman edit profil untuk perubahan yang tersedia.
- **Pengumuman** dan **FAQ** dapat dibuka dari menu masing-masing.
- Pada halaman login, pilih tautan **Lupa Password**, lalu masukkan nomor WhatsApp yang terdaftar. Mekanisme aplikasi mengirim password baru melalui WhatsApp; bila nomor tidak ditemukan atau akun belum aktif, hubungi admin.

## 4. Panduan admin

### 4.1 Menyiapkan katalog dan soal

1. Buka **Master Data → Kategori**, buat kategori dan lengkapi gambar/informasi yang diperlukan.
2. Buka **Kategori Penilaian**, buat bagian ujian untuk kategori yang sesuai. Jika memakai konversi nilai, lengkapi detail rentang nilai.
3. Buka **Bank Soal**, buat judul soal, pilih kategori, tetapkan jumlah pilihan dan jumlah section, serta pengaturan penilaian/koreksi.
4. Dari judul soal, kelola daftar soal. Pastikan setiap soal ditautkan ke kategori penilaian/section yang benar dan kunci jawaban valid. Untuk listening, lengkapi audio dan pengaturan pemutarannya.
5. Buka **Ujian → Tambah**, pilih kategori dan bank soal yang sesuai. Isi judul, deskripsi/instruksi, harga (0 untuk gratis), mode durasi, serta preferensi acak/navigasi/toleransi.
6. Simpan, lalu periksa detail ujian dan data soal sebelum dipublikasikan.

### 4.2 Mengelola rekening dan transaksi

1. Tambahkan/perbarui rekening pada **Rekening Bank** agar instruksi pembayaran di faktur benar.
2. Periksa **Transaksi** dan buka detail berdasarkan nomor faktur.
3. Cocokkan bukti pembayaran yang dikirim pengguna.
4. Ubah status menjadi **Lunas/paid** setelah pembayaran terverifikasi. Setelah proses final siap, ubah menjadi **Selesai/done**. Sistem membuat token voucher dan mengirimkannya melalui WhatsApp ke nomor peserta yang terdaftar.
5. Gunakan **Gagal/failed** bila transaksi memang dibatalkan. Beri tahu pengguna jika perlu melakukan pembelian ulang.

Jangan tandai transaksi sebagai selesai sebelum pembayaran diverifikasi. Token dikirim otomatis ketika status diubah ke `done`; status `paid` saja belum menerbitkan token. Jika peserta tidak menerima pesan, periksa nomor WhatsApp dan status/token transaksi sebelum mengambil tindakan. Jangan mengubah status ke `done` berulang kali tanpa memeriksa transaksi karena sistem membuat token baru saat status tersebut disimpan.

### 4.3 Mengelola akun dan informasi

- **Pengguna**: cari akun, lihat/edit data, aktifkan atau kelola status sesuai kebutuhan. Tersedia tindakan untuk mengirim pengingat aktivasi/member melalui WhatsApp.
- **Member**: lihat dan perbarui data siswa/member.
- **Pengumuman** dan **FAQ**: buat atau perbarui informasi yang ditampilkan pada sisi pengguna.
- Dashboard admin menyediakan ringkasan operasional.

## 5. Aturan sistem yang perlu diketahui

- Bagian admin dan pengguna memerlukan login dan akun aktif. Rute administrasi dibatasi untuk peran admin.
- Registrasi publik membuat peran pengguna biasa (`level 2`) dan status awal non-member.
- Status member adalah pemeriksaan global saat memulai ujian; aplikasi juga memeriksa adanya transaksi yang sudah diaktifkan untuk ujian berbayar.
- Jawaban ujian disimpan saat dikirim, sementara hasil final baru masuk riwayat setelah ujian selesai.
- Opsi pengacakan soal dan pilihan ditetapkan pada konfigurasi ujian dan diterapkan saat sesi dibuat.
- Nilai bergantung pada konfigurasi bank soal/kategori penilaian. Karena itu admin sebaiknya menguji satu sesi contoh sebelum ujian dibuka.

## 6. Catatan keterbatasan dan risiko kebingungan

Hal-hal berikut tampak dari implementasi dan penting untuk diketahui saat mengoperasikan sistem:

1. **Status member vs akses per kategori:** aktivasi menyimpan akses kategori pada data member, tetapi middleware mulai ujian hanya memeriksa flag member global. Jangan mengandalkan flag per kategori sebagai pembatas akses; verifikasi aturan akses yang diinginkan sebelum menjual paket dengan batas kategori tertentu.
2. **Alur ujian gratis belum konsisten:** endpoint transaksi dapat membuat transaksi gratis berstatus selesai beserta token, tetapi daftar ujian gratis di antarmuka langsung menampilkan tombol **Kerjakan Soal**, bukan tombol untuk membuat transaksi. Sementara itu, proses mulai tetap memeriksa status member. Pengguna baru/non-member dapat memerlukan bantuan admin; jangan mengasumsikan transaksi/token gratis dibuat otomatis dari katalog.
3. **Menu lama/nonaktif:** beberapa tautan pada sidebar/layout lama mengarah ke fitur yang tidak terdaftar pada rute aktif. Gunakan menu pada dashboard saat ini: Ujian, Riwayat Ujian, Riwayat Transaksi, Aktivasi Voucher, Profil, Pengumuman, dan FAQ.
4. **Timer dan toleransi keluar:** perpindahan fokus dapat mengurangi toleransi dan menghabiskan toleransi akan memblokir/menyelesaikan ujian. Beri tahu pengguna untuk menutup aplikasi lain dan menyiapkan perangkat/audio sebelum mulai.
5. **Penilaian perlu diuji:** terdapat beberapa mode penilaian di backend, sedangkan form bank soal yang terlihat hanya menawarkan sebagian pilihan. Admin sebaiknya memastikan konfigurasi penilaian dan data kategori cocok sebelum sesi dipakai untuk nilai resmi.
6. **Reset/mengulang ujian:** tersedia alur mulai ulang yang menghapus sesi dan rincian nilai sebelumnya untuk ujian itu pada akun pengguna. Pengguna sebaiknya mengulang hanya setelah memahami dampak terhadap riwayat sesi sebelumnya.
7. **Reset password melalui WhatsApp:** aplikasi mengirim password baru melalui WhatsApp. Pastikan nomor telepon akun benar dan gunakan kanal admin jika nomor sudah tidak dapat diakses.

## 7. Peta kode untuk pemelihara

| Area | Lokasi utama |
|---|---|
| Rute admin dan pengguna | `routes/web.php` |
| Registrasi, aktivasi, transaksi, dan ujian | `app/Actions/Fortify/CreateNewUser.php`, `app/Http/Controllers/ActivationController.php`, `app/Http/Controllers/User/Transaction/`, `app/Http/Controllers/User/TryOut/` |
| Administrasi soal dan transaksi | `app/Http/Controllers/Admin/` |
| Pembuatan transaksi/token | `app/Services/Transaction/TransactionService.php` |
| Penyusunan ujian dan penilaian | `app/Services/Exam/ExamService.php`, `app/Http/Controllers/User/TryOut/ExamController.php` |
| Antarmuka pengguna | `resources/js/Pages/User/` |
| Antarmuka admin | `resources/js/Pages/Admin/` |
