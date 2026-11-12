# Panduan Setup Project E-TOEFL di WSL (Ubuntu)

Panduan ini disusun berdasarkan hasil analisis dependensi dan konfigurasi spesifik pada proyek ini (`composer.json`, `composer.lock`, `package.json`, database migrasi, dan seeders).

---

## 1. Spesifikasi Stack & Versi Kebutuhan

| Komponen / Tools | Versi yang Harus Digunakan | Alasan & Catatan Teknis |
| :--- | :--- | :--- |
| **Sistem Operasi** | **Ubuntu 22.04 LTS / 24.04 LTS** (WSL 2) | Standar distribusi Ubuntu pada WSL. |
| **PHP** | **PHP 8.2** *(Kritis)* | **Wajib gunakan PHP 8.2.** Pada `composer.lock`, dependensi seperti `nette/schema` dan `nette/utils` membatasi versi PHP `< 8.3`. Jika menggunakan PHP 8.3/8.4 bawaan Ubuntu terbaru, `composer install` akan gagal. |
| **PHP Extensions** | `cli`, `common`, `fpm`, `mysql`, `mbstring`, `xml`, `curl`, `zip`, `bcmath`, `gd`, `intl`, `soap` | Dibutuhkan oleh core Laravel 10, DOMPDF (`barryvdh/laravel-dompdf`), Larapex Charts, dan manipulasi data string/gambar. |
| **Composer** | **v2.x** (v2.7+) | Package manager PHP modern. |
| **Node.js** | **v20.x LTS** (atau v18.x) | Proyek menggunakan **Vite 3** dan **Vue 3** + **Inertia.js**. Node 20 LTS direkomendasikan untuk stabilitas build. Disarankan di-install via NVM. |
| **NPM** | **v10.x** (bawaan Node 20) | Package manager frontend. |
| **Database** | **MySQL 8.0** atau **MariaDB 10.11+** | Sesuai konfigurasi default pada `.env.example`. |
| **Utilitas Tambahan** | `git`, `curl`, `unzip`, `dos2unix` | Membantu instalasi dependensi dan mengonversi line-ending Windows (CRLF) ke Linux (LF). |

---

## 2. Langkah Instalasi Lengkap (Terminal Baru Ubuntu WSL)

Buka terminal Ubuntu WSL Anda dan jalankan perintah berikut secara berurutan:

### Langkah 1: Update Sistem & Install Tool Dasar
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y curl git unzip zip software-properties-common ca-certificates lsb-release dos2unix
```

---

### Langkah 2: Tambahkan PPA & Install PHP 8.2 Beserta Ekstensi
Ubuntu versi baru sering kali menginstal PHP 8.3 secara default. Gunakan repository resmi Ondřej Surý agar bisa memilih PHP 8.2 secara spesifik:

```bash
# Tambahkan PPA Ondrej PHP
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP 8.2 dan seluruh ekstensi yang diperlukan
sudo apt install -y php8.2 php8.2-cli php8.2-common php8.2-fpm \
php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl \
php8.2-zip php8.2-bcmath php8.2-gd php8.2-intl php8.2-soap

# Verifikasi versi PHP
php -v
```

---

### Langkah 3: Install Composer (v2.x)
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Verifikasi versi Composer
composer --version
```

---

### Langkah 4: Install Node.js v20 LTS via NVM
Menggunakan NVM menghindarkan masalah izin akses (*permission denied*) saat menginstal paket global/lokal npm:

```bash
# Download & pasang NVM
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash

# Aktifkan NVM pada sesi terminal saat ini
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"

# Install Node.js 20 LTS
nvm install 20
nvm use 20
nvm alias default 20

# Verifikasi Node & NPM
node -v
npm -v
```

---

### Langkah 5: Install & Setup Database MySQL
Jika Anda ingin MySQL berjalan langsung di dalam lingkungan WSL:

```bash
# Install MySQL Server
sudo apt install -y mysql-server

# Nyalakan service MySQL
sudo service mysql start

# Buka console MySQL
sudo mysql
```

Di dalam prompt MySQL, buat database dan user baru:
```sql
CREATE DATABASE etoefl;
CREATE USER 'etoefl_user'@'localhost' IDENTIFIED BY 'password123';
GRANT ALL PRIVILEGES ON etoefl.* TO 'etoefl_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

### Langkah 6: Masuk ke Folder Project
Path Windows `E:\Web KU\laravel-server\e-toefl` diakses melalui folder `/mnt/`:

```bash
cd "/mnt/e/Web KU/laravel-server/e-toefl"
```

> **Tips Performa WSL 2:**  
> Menjalankan proyek langsung di `/mnt/e/...` tetap berfungsi normal. Namun, jika ingin kecepatan I/O *build* dan *composer* yang jauh lebih cepat, Anda bisa memindahkan atau clone proyek ke direktori Linux WSL asli (seperti `~/projects/e-toefl`).

---

### Langkah 7: Install Dependensi Proyek

1. **Konversi Line-Ending script Artisan (CRLF -> LF):**
   ```bash
   dos2unix artisan
   ```

2. **Install Composer Dependencies:**
   ```bash
   composer install
   ```

3. **Install NPM Dependencies:**
   ```bash
   npm install
   ```

---

### Langkah 8: Konfigurasi Environment & Database

1. **Salin file konfigurasi `.env` dan generate APP_KEY:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

2. **Sesuaikan kredensial database di file `.env`:**  
   Buka file `.env` dengan editor terminal (misal: `nano .env`) dan sesuaikan bagian database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=etoefl
   DB_USERNAME=etoefl_user
   DB_PASSWORD=password123
   ```

3. **Buat Symlink Public Storage:**
   ```bash
   php artisan storage:link
   ```

4. **Jalankan Migrasi & Database Seeder:**  
   > **Perhatian:** Proyek ini menyertakan database seeder geografis yang besar (`VillageSeeder.php` sekitar 4 MB). Naikkan alokasi memori sementara saat seeding agar tidak terjadi error *memory exhausted*:
   ```bash
   php -d memory_limit=1024M artisan migrate --seed
   ```

---

### Langkah 9: Menjalankan Server Development

Buka dua terminal WSL terpisah di direktori project:

* **Terminal 1 (Backend Laravel):**
  ```bash
  php artisan serve
  ```
  Akses di browser Windows: `http://127.0.0.1:8000`

* **Terminal 2 (Frontend Vite Asset Server):**
  ```bash
  npm run dev
  ```

---

## 3. Catatan & Solusi Kendala Umum (Troubleshooting)

1. **Service MySQL Mati Setelah Reboot WSL:**  
   Service di WSL tidak otomatis berjalan seperti di Windows Services. Cukup jalankan kembali:
   ```bash
   sudo service mysql start
   ```

2. **Error `bad interpreter: /bin/sh^M` saat menjalankan `./artisan`:**  
   Disebabkan oleh line-ending Windows CRLF. Jalankan:
   ```bash
   dos2unix artisan
   ```

3. **PHP Memory Limit Error saat Seeding:**  
   Jika migrasi atau seeder gagal dengan pesan `Allowed memory size exhausted`, selalu sertakan flag memori tambahan:
   ```bash
   php -d memory_limit=1024M artisan db:seed
   ```

